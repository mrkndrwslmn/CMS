<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    /**
     * Display payment history with filters
     */
    public function index(Request $request)
    {
        $query = Payment::with(['serviceRequest', 'confirmedBy'])
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('serviceRequest', function($sq) use ($search) {
                      $sq->where('project_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get statistics
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'confirmed' => Payment::where('status', 'confirmed')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'confirmed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
        ];

        // Get monthly revenue (last 6 months) - Database agnostic approach
        $monthlyRevenue = Payment::where('status', 'confirmed')
            ->where('confirmed_at', '>=', now()->subMonths(6))
            ->select(
                config('database.default') === 'sqlite' 
                    ? DB::raw("strftime('%Y-%m', confirmed_at) as month")
                    : DB::raw('DATE_FORMAT(confirmed_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get payment method distribution
        $paymentMethods = Payment::select('payment_method', DB::raw('count(*) as count'), DB::raw('SUM(amount) as total'))
            ->where('status', 'confirmed')
            ->groupBy('payment_method')
            ->get();

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments', 'stats', 'monthlyRevenue', 'paymentMethods'));
    }

    /**
     * Display single payment details
     */
    public function show($id)
    {
        $payment = Payment::with(['serviceRequest.client', 'confirmedBy'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update payment status (for manual confirmation/refund)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,failed,refunded,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment = Payment::findOrFail($id);
        
        $oldStatus = $payment->status;
        $payment->status = $request->status;
        
        if ($request->filled('notes')) {
            $payment->notes = $payment->notes 
                ? $payment->notes . "\n\n" . now()->format('Y-m-d H:i') . ": " . $request->notes
                : $request->notes;
        }

        // Set confirmed_at and confirmed_by if confirming
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            $payment->confirmed_at = now();
            $payment->confirmed_by = auth()->id();
            
            // Process referral completion when payment is confirmed
            try {
                $referralService = app(\App\Services\ReferralService::class);
                $referralService->processReferralCompletion($payment);
                
                \Log::info('Referral completion processed after admin payment confirmation', [
                    'payment_id' => $payment->id,
                    'client_id' => $payment->client_id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to process referral completion after admin confirmation', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $payment->save();

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = Payment::with(['serviceRequest', 'confirmedBy'])->latest();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->get();

        $filename = 'payments_export_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Payment ID',
                'Reference',
                'Transaction ID',
                'Project Name',
                'Client',
                'Amount',
                'Payment Method',
                'Status',
                'Created At',
                'Confirmed At',
                'Confirmed By',
            ]);

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_reference,
                    $payment->transaction_id,
                    $payment->serviceRequest->project_name ?? 'N/A',
                    $payment->serviceRequest->client->fullName ?? 'N/A',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->status,
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->confirmed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $payment->confirmedBy->fullName ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
