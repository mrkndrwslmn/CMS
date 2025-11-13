<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmed;
use App\Services\MayaPaymentService;
use App\Services\LoyaltyService;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\PaymentConfirmedNotification;

class MayaPaymentController extends Controller
{
    protected MayaPaymentService $mayaService;
    protected LoyaltyService $loyaltyService;
    protected CouponService $couponService;

    public function __construct(
        MayaPaymentService $mayaService,
        LoyaltyService $loyaltyService,
        CouponService $couponService
    ) {
        $this->mayaService = $mayaService;
        $this->loyaltyService = $loyaltyService;
        $this->couponService = $couponService;
    }

    /**
     * Initiate Maya checkout for a service request
     */
    public function checkout($serviceRequestId)
    {
        $user = Auth::user();

        // Get service request using Eloquent to access relationships and methods
        $serviceRequest = \App\Models\ServiceRequest::with('project.milestones')
            ->where('id', $serviceRequestId)
            ->where('client_id', $user->id)
            ->whereIn('status', ['pending_payment', 'approved', 'in_progress', 'paid'])
            ->first();

        if (!$serviceRequest) {
            return redirect()->route('client.requests')
                ->with('error', 'Service request not found or not available for payment.');
        }

        // Get current payment amount due based on payment type
        $amount = $serviceRequest->getCurrentPaymentAmountDue();
        $paymentDescription = $serviceRequest->getCurrentPaymentDescription();

        if ($amount <= 0) {
            return redirect()->route('client.requests.show', $serviceRequestId)
                ->with('error', 'No payment is currently due. Please contact admin if you believe this is an error.');
        }

        // Create unique reference number
        $referenceNumber = 'TREIS-' . time() . '-' . $serviceRequestId;

        // Prepare items array for Maya
        $items = [
            [
                'name' => $serviceRequest->project_name . ' - ' . $paymentDescription,
                'quantity' => 1,
                'code' => 'SERVICE-' . $serviceRequestId,
                'description' => substr($serviceRequest->request_description, 0, 100),
                'amount' => [
                    'value' => $amount,
                    'details' => [
                        'discount' => 0,
                        'serviceCharge' => 0,
                        'shippingFee' => 0,
                        'tax' => 0,
                        'subtotal' => $amount
                    ]
                ],
                'totalAmount' => [
                    'value' => $amount,
                    'details' => [
                        'discount' => 0,
                        'serviceCharge' => 0,
                        'shippingFee' => 0,
                        'tax' => 0,
                        'subtotal' => $amount
                    ]
                ]
            ]
        ];

        // Prepare buyer information
        $buyer = [
            'firstName' => explode(' ', $user->fullName)[0] ?? 'Client',
            'lastName' => explode(' ', $user->fullName, 2)[1] ?? '',
            'contact' => [
                'email' => $user->email
            ]
        ];

        // Prepare redirect URLs
        $redirectUrls = [
            'success' => route('client.maya.success', ['ref' => $referenceNumber]),
            'failure' => route('client.maya.failure', ['ref' => $referenceNumber]),
            'cancel' => route('client.maya.cancel', ['ref' => $referenceNumber])
        ];

        // Create Maya checkout
        $response = $this->mayaService->createCheckout(
            $items,
            $amount,
            $referenceNumber,
            $buyer,
            $redirectUrls
        );

        if ($response['error']) {
            Log::error('Maya checkout creation failed', [
                'service_request_id' => $serviceRequestId,
                'error' => $response['message']
            ]);

            return redirect()->route('client.requests.show', $serviceRequestId)
                ->with('error', 'Failed to initiate payment. Please try again or contact support.');
        }

        // Store payment record
        try {
            // Determine payment type and milestone (if applicable)
            $paymentType = $serviceRequest->payment_type ?? 'full_payment';
            $milestoneId = null;

            if ($serviceRequest->isMilestonePayment() && $serviceRequest->project) {
                $nextUnpaidMilestone = $serviceRequest->project->milestones()
                    ->where('is_paid', false)
                    ->orderBy('phase_order', 'asc')
                    ->first();
                
                if ($nextUnpaidMilestone) {
                    $milestoneId = $nextUnpaidMilestone->id;
                }
            }

            DB::table('payments')->insert([
                'service_request_id' => $serviceRequestId,
                'client_id' => $user->id,
                'amount' => $amount,
                'payment_method' => 'maya',
                'payment_reference' => $referenceNumber,
                'payment_type' => $paymentType,
                'milestone_id' => $milestoneId,
                'status' => 'pending',
                'payment_details' => json_encode([
                    'checkout_id' => $response['data']['checkoutId'],
                    'environment' => $this->mayaService->getEnvironment(),
                    'payment_description' => $paymentDescription
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);;

            // Update service request - keep status as pending_payment until Maya confirms
            DB::table('service_requests')
                ->where('id', $serviceRequestId)
                ->update([
                    'payment_reference' => $referenceNumber,
                    'status' => 'pending_payment',
                    'updated_at' => now()
                ]);

            Log::info('Maya payment initiated', [
                'service_request_id' => $serviceRequestId,
                'reference' => $referenceNumber,
                'checkout_id' => $response['data']['checkoutId']
            ]);

            // Redirect to Maya checkout page
            return redirect()->away($response['data']['redirectUrl']);
        } catch (\Exception $e) {
            Log::error('Failed to store payment record', [
                'service_request_id' => $serviceRequestId,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('client.requests.show', $serviceRequestId)
                ->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $referenceNumber = $request->query('ref');

        if (empty($referenceNumber)) {
            return redirect()->route('client.requests')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            // Get payment record
            $payment = DB::table('payments')
                ->where('payment_reference', $referenceNumber)
                ->first();

            if (!$payment) {
                Log::warning('Payment not found', ['reference' => $referenceNumber]);
                return redirect()->route('client.requests')
                    ->with('error', 'Payment record not found.');
            }

            // Verify payment with Maya
            $paymentDetails = json_decode($payment->payment_details, true);
            $checkoutId = $paymentDetails['checkout_id'] ?? null;

            if ($checkoutId) {
                $verificationResponse = $this->mayaService->verifyPayment($checkoutId);

                if (!$verificationResponse['error']) {
                    $mayaPaymentData = $verificationResponse['data'];
                    $transactionId = $mayaPaymentData['id'] ?? null;

                    // Update payment status
                    DB::table('payments')
                        ->where('id', $payment->id)
                        ->update([
                            'status' => 'confirmed',
                            'transaction_id' => $transactionId,
                            'gateway_response' => json_encode($mayaPaymentData),
                            'confirmed_at' => now(),
                            'updated_at' => now()
                        ]);

                    // Get service request model to access payment type methods
                    $serviceRequest = \App\Models\ServiceRequest::with('project.milestones')->find($payment->service_request_id);

                    // Handle payment based on payment type
                    if ($serviceRequest) {
                        $this->handlePaymentConfirmation($serviceRequest, $payment);
                    } else {
                        // Fallback: update service request to paid status
                        DB::table('service_requests')
                            ->where('id', $payment->service_request_id)
                            ->update([
                                'status' => 'paid',
                                'payment_confirmed_at' => now(),
                                'updated_at' => now()
                            ]);
                    }

                    // Check if project already exists
                    $existingProject = DB::table('projects')
                        ->where('service_request_id', $payment->service_request_id)
                        ->first();

                    if (!$existingProject) {
                        // Create project from service request
                        $serviceRequest = DB::table('service_requests')->find($payment->service_request_id);
                        
                        $projectId = DB::table('projects')->insertGetId([
                            'service_request_id' => $payment->service_request_id,
                            'client_id' => $payment->client_id,
                            'title' => $serviceRequest->project_name,
                            'description' => $serviceRequest->request_description,
                            'budget' => $payment->amount,
                            'deadline' => $serviceRequest->deadline,
                            'status' => 'active',
                            'priority' => $serviceRequest->priority ?? 'medium',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        Log::info('Project created from payment', [
                            'project_id' => $projectId,
                            'service_request_id' => $payment->service_request_id
                        ]);
                    } else {
                        $projectId = $existingProject->id;
                        Log::info('Project already exists for service request', [
                            'project_id' => $projectId,
                            'service_request_id' => $payment->service_request_id
                        ]);
                    }

                    // Notify client about payment confirmation
                    $clientUser = \App\Models\User::find($payment->client_id);
                    if ($clientUser) {
                        try {
                            Log::info('Dispatching PaymentConfirmedNotification to client', [
                                'payment_id' => $payment->id,
                                'client_id' => $clientUser->id,
                                'client_email' => $clientUser->email,
                                'amount' => $payment->amount
                            ]);
                            
                            $clientUser->notify(new PaymentConfirmedNotification($payment));
                            
                            Log::info('PaymentConfirmedNotification dispatched to client successfully', [
                                'payment_id' => $payment->id,
                                'client_id' => $clientUser->id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to notify client about payment confirmation', [
                                'payment_id' => $payment->id,
                                'client_id' => $clientUser->id,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }
                    }

                    // Award loyalty points for payment
                    if ($serviceRequest && $clientUser) {
                        try {
                            $paymentModel = \App\Models\Payment::find($payment->id);
                            $this->loyaltyService->awardPointsForPayment($serviceRequest, $paymentModel);
                            
                            Log::info('Loyalty points awarded for payment', [
                                'payment_id' => $payment->id,
                                'service_request_id' => $serviceRequest->id,
                                'client_id' => $clientUser->id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to award loyalty points', [
                                'payment_id' => $payment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }

                        // 🎁 Process referral completion (if this is user's first payment)
                        try {
                            $referralService = app(\App\Services\ReferralService::class);
                            $paymentModel = \App\Models\Payment::find($payment->id);
                            $referralService->processReferralCompletion($paymentModel);
                            
                            Log::info('Referral completion processed for payment', [
                                'payment_id' => $payment->id,
                                'client_id' => $clientUser->id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to process referral completion', [
                                'payment_id' => $payment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Update coupon usage status
                    if ($serviceRequest && $serviceRequest->applied_coupon_id) {
                        try {
                            $this->couponService->updateCouponUsageStatus($serviceRequest, 'completed');
                            
                            Log::info('Coupon usage marked as completed', [
                                'payment_id' => $payment->id,
                                'service_request_id' => $serviceRequest->id,
                                'coupon_id' => $serviceRequest->applied_coupon_id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to update coupon usage status', [
                                'payment_id' => $payment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Notify admins using Laravel's notification structure
                    $admins = User::where('role', 'admin')->get();
                    Log::info('Dispatching PaymentConfirmedNotification to admins', [
                        'payment_id' => $payment->id,
                        'admin_count' => $admins->count(),
                        'amount' => $payment->amount
                    ]);
                    
                    foreach ($admins as $admin) {
                        try {
                            $admin->notify(new PaymentConfirmedNotification($payment));
                            
                            Log::debug('PaymentConfirmedNotification dispatched to admin', [
                                'payment_id' => $payment->id,
                                'admin_id' => $admin->id,
                                'admin_email' => $admin->email
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to notify admin about payment confirmation', [
                                'payment_id' => $payment->id,
                                'admin_id' => $admin->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    Log::info('Payment confirmed successfully', [
                        'reference' => $referenceNumber,
                        'project_id' => $projectId
                    ]);
                }
            }

            // Show success page
            return view('client.payments.maya-success', [
                'payment' => $payment,
                'serviceRequest' => DB::table('service_requests')->find($payment->service_request_id)
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing payment success', [
                'reference' => $referenceNumber,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('client.requests')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle failed payment
     */
    public function failure(Request $request)
    {
        $referenceNumber = $request->query('ref');

        if (!empty($referenceNumber)) {
            // Update payment status
            DB::table('payments')
                ->where('payment_reference', $referenceNumber)
                ->update([
                    'status' => 'failed',
                    'updated_at' => now()
                ]);

            // Get service request ID
            $payment = DB::table('payments')
                ->where('payment_reference', $referenceNumber)
                ->first();

            if ($payment) {
                // Update service request status back to pending_payment
                DB::table('service_requests')
                    ->where('id', $payment->service_request_id)
                    ->update([
                        'status' => 'pending_payment',
                        'updated_at' => now()
                    ]);
            }

            Log::warning('Payment failed', ['reference' => $referenceNumber]);
        }

        return view('client.payments.maya-failure', [
            'referenceNumber' => $referenceNumber
        ]);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        $referenceNumber = $request->query('ref');

        if (!empty($referenceNumber)) {
            // Update payment status
            DB::table('payments')
                ->where('payment_reference', $referenceNumber)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);

            // Get service request ID
            $payment = DB::table('payments')
                ->where('payment_reference', $referenceNumber)
                ->first();

            if ($payment) {
                // Update service request status back to pending_payment
                DB::table('service_requests')
                    ->where('id', $payment->service_request_id)
                    ->update([
                        'status' => 'pending_payment',
                        'updated_at' => now()
                    ]);
            }

            Log::info('Payment cancelled by user', ['reference' => $referenceNumber]);
        }

        return view('client.payments.maya-cancel', [
            'referenceNumber' => $referenceNumber
        ]);
    }

    /**
     * Handle payment confirmation based on payment type
     */
    protected function handlePaymentConfirmation($serviceRequest, $payment)
    {
        // Full payment
        if ($serviceRequest->isFullPayment() || !$serviceRequest->payment_type) {
            DB::table('service_requests')
                ->where('id', $serviceRequest->id)
                ->update([
                    'status' => 'paid',
                    'payment_confirmed_at' => now(),
                    'updated_at' => now()
                ]);
            
            Log::info('Full payment confirmed', [
                'service_request_id' => $serviceRequest->id
            ]);
            return;
        }

        // Downpayment
        if ($serviceRequest->isDownpayment()) {
            if (!$serviceRequest->downpayment_paid) {
                // This is the downpayment
                DB::table('service_requests')
                    ->where('id', $serviceRequest->id)
                    ->update([
                        'downpayment_paid' => true,
                        'downpayment_paid_at' => now(),
                        'status' => 'in_progress', // Start project after downpayment
                        'payment_confirmed_at' => now(),
                        'updated_at' => now()
                    ]);
                
                Log::info('Downpayment confirmed', [
                    'service_request_id' => $serviceRequest->id,
                    'amount' => $payment->amount
                ]);
            } elseif (!$serviceRequest->remaining_balance_paid) {
                // This is the remaining balance
                DB::table('service_requests')
                    ->where('id', $serviceRequest->id)
                    ->update([
                        'remaining_balance_paid' => true,
                        'remaining_balance_paid_at' => now(),
                        'status' => 'paid', // Fully paid
                        'updated_at' => now()
                    ]);
                
                Log::info('Remaining balance paid', [
                    'service_request_id' => $serviceRequest->id,
                    'amount' => $payment->amount
                ]);
            }
            return;
        }

        // Milestone payment
        if ($serviceRequest->isMilestonePayment()) {
            // Find the milestone that was paid
            if ($payment->milestone_id) {
                $milestone = \App\Models\ProjectMilestone::find($payment->milestone_id);
                
                if ($milestone) {
                    // Mark milestone as paid
                    $milestone->update([
                        'is_paid' => true,
                        'paid_at' => now()
                    ]);

                    // Create milestone payment record
                    \App\Models\MilestonePayment::create([
                        'milestone_id' => $milestone->id,  // Corrected field name
                        'service_request_id' => $serviceRequest->id,
                        'payment_id' => $payment->id,
                        'client_id' => $payment->client_id,
                        'amount_due' => $milestone->amount,
                        'amount_paid' => $payment->amount,
                        'status' => 'paid',
                        'due_date' => $serviceRequest->payment_due_date,
                        'paid_at' => now()
                    ]);

                    Log::info('Milestone payment confirmed', [
                        'service_request_id' => $serviceRequest->id,
                        'milestone_id' => $milestone->id,
                        'phase' => $milestone->phase_order,
                        'amount' => $payment->amount
                    ]);

                    // Check if all milestones are paid
                    $unpaidMilestones = $serviceRequest->project->milestones()
                        ->where('is_paid', false)
                        ->count();

                    if ($unpaidMilestones === 0) {
                        // All milestones paid - mark as fully paid
                        DB::table('service_requests')
                            ->where('id', $serviceRequest->id)
                            ->update([
                                'status' => 'paid',
                                'payment_confirmed_at' => now(),
                                'updated_at' => now()
                            ]);
                        
                        Log::info('All milestones paid', [
                            'service_request_id' => $serviceRequest->id
                        ]);
                    } else {
                        // Still has unpaid milestones - keep in progress or pending_payment
                        DB::table('service_requests')
                            ->where('id', $serviceRequest->id)
                            ->update([
                                'status' => 'in_progress',
                                'updated_at' => now()
                            ]);
                    }
                }
            }
            return;
        }

        // Default fallback
        DB::table('service_requests')
            ->where('id', $serviceRequest->id)
            ->update([
                'status' => 'paid',
                'payment_confirmed_at' => now(),
                'updated_at' => now()
            ]);
    }
}
