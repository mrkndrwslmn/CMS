<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ServiceRequest;
use App\Models\MilestonePayment;
use App\Models\Payment;
use App\Mail\MilestoneStarted;
use App\Mail\MilestoneCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;

class MilestoneService
{
    /**
     * Create milestones for a project based on phases configuration
     * 
     * @param Project $project
     * @param array $phases Array of phase data with name, percentage, description, etc.
     * @return array Created milestones
     */
    public function createMilestones(Project $project, array $phases): array
    {
        $createdMilestones = [];
        $serviceRequest = $project->serviceRequest;
        $projectBudget = $project->budget ?? $serviceRequest->approved_budget ?? 0;

        DB::beginTransaction();

        try {
            foreach ($phases as $index => $phaseData) {
                $phaseOrder = $index + 1;
                $percentage = $phaseData['percentage'] ?? 0;
                $amount = round(($projectBudget * $percentage) / 100, 2);

                $milestone = ProjectMilestone::create([
                    'project_id' => $project->id,
                    'phase_name' => $phaseData['name'] ?? "Phase {$phaseOrder}",
                    'phase_description' => $phaseData['description'] ?? null,
                    'phase_order' => $phaseOrder,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'start_date' => $phaseData['start_date'] ?? null,
                    'due_date' => $phaseData['due_date'] ?? null,
                    'status' => $phaseOrder === 1 ? 'pending' : 'pending',
                    'is_paid' => false,
                    'notes' => $phaseData['notes'] ?? null,
                    'deliverables' => $phaseData['deliverables'] ?? null,
                ]);

                // Create corresponding milestone payment record
                MilestonePayment::create([
                    'milestone_id' => $milestone->id,
                    'service_request_id' => $serviceRequest->id,
                    'client_id' => $project->client_id,
                    'amount_due' => $amount,
                    'amount_paid' => 0,
                    'status' => 'pending',
                    'due_date' => $phaseData['due_date'] ?? null,
                ]);

                $createdMilestones[] = $milestone;
            }

            // Update service request with milestone count
            $serviceRequest->update([
                'total_milestones' => count($phases),
            ]);

            DB::commit();

            return $createdMilestones;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process a milestone payment
     * 
     * @param ProjectMilestone $milestone
     * @param Payment $payment
     * @param int|null $confirmedBy Admin user ID
     * @return bool
     */
    public function processMilestonePayment(ProjectMilestone $milestone, Payment $payment, ?int $confirmedBy = null): bool
    {
        DB::beginTransaction();

        try {
            // Update the payment with milestone information
            $payment->update([
                'milestone_id' => $milestone->id,
                'payment_type' => 'milestone_payment',
            ]);

            // Mark milestone as paid
            $milestone->markAsPaid();

            // Update milestone payment record
            $milestonePayment = $milestone->milestonePayment;
            if ($milestonePayment) {
                $milestonePayment->markAsPaid($payment->id, $confirmedBy);
            }

            // If this is the first milestone, activate it
            if ($milestone->phase_order === 1) {
                $milestone->update(['status' => 'in_progress']);
                
                // Send milestone started email
                try {
                    $client = $milestone->project->client;
                    if ($client && $client->email) {
                        Mail::to($client->email)->send(new MilestoneStarted($milestone));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send milestone started email', [
                        'milestone_id' => $milestone->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Check if this was the last unpaid milestone
            $this->checkAndCompleteAllMilestones($milestone->project);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process downpayment
     * 
     * @param ServiceRequest $serviceRequest
     * @param Payment $payment
     * @return bool
     */
    public function processDownpayment(ServiceRequest $serviceRequest, Payment $payment): bool
    {
        DB::beginTransaction();

        try {
            // Calculate downpayment amount if not set
            if (!$serviceRequest->downpayment_amount) {
                $downpaymentAmount = $serviceRequest->calculateDownpaymentAmount();
                $remainingBalance = $serviceRequest->calculateRemainingBalance();

                $serviceRequest->update([
                    'downpayment_amount' => $downpaymentAmount,
                    'remaining_balance' => $remainingBalance,
                ]);
            }

            // Mark downpayment as paid
            $serviceRequest->update([
                'downpayment_paid' => true,
                'downpayment_paid_at' => now(),
            ]);

            // Update payment type
            $payment->update([
                'payment_type' => 'downpayment',
            ]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process remaining balance payment
     * 
     * @param ServiceRequest $serviceRequest
     * @param Payment $payment
     * @return bool
     */
    public function processRemainingBalance(ServiceRequest $serviceRequest, Payment $payment): bool
    {
        DB::beginTransaction();

        try {
            // Mark remaining balance as paid
            $serviceRequest->update([
                'remaining_balance_paid' => true,
                'remaining_balance_paid_at' => now(),
            ]);

            // Update payment type
            $payment->update([
                'payment_type' => 'remaining_balance',
            ]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if all milestones are paid and update project status accordingly
     * 
     * @param Project $project
     * @return void
     */
    protected function checkAndCompleteAllMilestones(Project $project): void
    {
        $totalMilestones = $project->milestones()->count();
        $paidMilestones = $project->paidMilestones()->count();

        if ($totalMilestones > 0 && $totalMilestones === $paidMilestones) {
            // All milestones are paid - project is fully funded
            $project->serviceRequest->update([
                'status' => 'paid',
            ]);
        }
    }

    /**
     * Get next payable milestone for a project
     * 
     * @param Project $project
     * @return ProjectMilestone|null
     */
    public function getNextPayableMilestone(Project $project): ?ProjectMilestone
    {
        // For milestone payments, find the first unpaid milestone
        return $project->milestones()
            ->where('is_paid', false)
            ->orderBy('phase_order', 'asc')
            ->first();
    }

    /**
     * Update milestone status based on completion
     * 
     * @param ProjectMilestone $milestone
     * @param string $status
     * @return bool
     */
    public function updateMilestoneStatus(ProjectMilestone $milestone, string $status): bool
    {
        $milestone->update(['status' => $status]);

        // If marking as completed, check if next milestone should be activated
        if ($status === 'completed') {
            // Update completion timestamp
            $milestone->update(['completed_at' => now()]);
            
            // Send milestone completed email
            try {
                $client = $milestone->project->client;
                if ($client && $client->email) {
                    Mail::to($client->email)->send(new MilestoneCompleted($milestone));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send milestone completed email', [
                    'milestone_id' => $milestone->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            $nextMilestone = $milestone->nextMilestone();
            if ($nextMilestone && $nextMilestone->isPaid()) {
                $nextMilestone->update(['status' => 'in_progress']);
                
                // Send next milestone started email
                try {
                    $client = $nextMilestone->project->client;
                    if ($client && $client->email) {
                        Mail::to($client->email)->send(new MilestoneStarted($nextMilestone));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send next milestone started email', [
                        'milestone_id' => $nextMilestone->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * Calculate total paid amount for a project
     * 
     * @param Project $project
     * @return float
     */
    public function getTotalPaidAmount(Project $project): float
    {
        return $project->paidMilestones()->sum('amount');
    }

    /**
     * Calculate total remaining amount for a project
     * 
     * @param Project $project
     * @return float
     */
    public function getTotalRemainingAmount(Project $project): float
    {
        return $project->unpaidMilestones()->sum('amount');
    }

    /**
     * Get payment progress percentage for a project
     * 
     * @param Project $project
     * @return float
     */
    public function getPaymentProgress(Project $project): float
    {
        $serviceRequest = $project->serviceRequest;

        if ($serviceRequest->isFullPayment()) {
            return $serviceRequest->isPaid() ? 100 : 0;
        }

        if ($serviceRequest->isMilestonePayment()) {
            $totalMilestones = $project->milestones()->count();
            $paidMilestones = $project->paidMilestones()->count();

            return $totalMilestones > 0 ? round(($paidMilestones / $totalMilestones) * 100, 2) : 0;
        }

        if ($serviceRequest->isDownpayment()) {
            if ($serviceRequest->isRemainingBalancePaid()) {
                return 100;
            }
            if ($serviceRequest->isDownpaymentPaid()) {
                return round(($serviceRequest->downpayment_amount / $serviceRequest->approved_budget) * 100, 2);
            }
            return 0;
        }

        return 0;
    }

    /**
     * Check if a specific task is accessible based on payment status
     * 
     * @param \App\Models\Task $task
     * @return bool
     */
    public function isTaskAccessible($task): bool
    {
        return $task->isAccessibleToClient();
    }

    /**
     * Check if a specific document is accessible based on payment status
     * 
     * @param \App\Models\Document $document
     * @return bool
     */
    public function isDocumentAccessible($document): bool
    {
        return $document->isAccessibleToClient();
    }

    /**
     * Get milestone summary for a project
     * 
     * @param Project $project
     * @return array
     */
    public function getMilestoneSummary(Project $project): array
    {
        $milestones = $project->orderedMilestones()->get();
        
        return [
            'total_milestones' => $milestones->count(),
            'paid_milestones' => $milestones->where('is_paid', true)->count(),
            'pending_milestones' => $milestones->where('is_paid', false)->count(),
            'total_amount' => $milestones->sum('amount'),
            'paid_amount' => $milestones->where('is_paid', true)->sum('amount'),
            'remaining_amount' => $milestones->where('is_paid', false)->sum('amount'),
            'progress_percentage' => $this->getPaymentProgress($project),
            'milestones' => $milestones,
        ];
    }
}
