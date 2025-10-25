<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{
    /**
     * Display client's payment history
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Payment::with(['serviceRequest'])
            ->where('client_id', $user->id)
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
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

        // Get statistics for client
        $stats = [
            'total' => Payment::where('client_id', $user->id)->count(),
            'confirmed' => Payment::where('client_id', $user->id)->where('status', 'confirmed')->count(),
            'pending' => Payment::where('client_id', $user->id)->where('status', 'pending')->count(),
            'total_spent' => Payment::where('client_id', $user->id)->where('status', 'confirmed')->sum('amount'),
        ];

        $payments = $query->paginate(15)->withQueryString();

        return view('client.payments.history', compact('payments', 'stats'));
    }

    /**
     * Display single payment details
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $payment = Payment::with(['serviceRequest'])
            ->where('client_id', $user->id)
            ->findOrFail($id);

        return view('client.payments.show', compact('payment'));
    }

    /**
     * Download payment receipt
     */
    public function receipt($id)
    {
        $user = Auth::user();
        
        $payment = Payment::with(['serviceRequest', 'confirmedBy'])
            ->where('client_id', $user->id)
            ->where('status', 'confirmed')
            ->findOrFail($id);

        return view('client.payments.receipt', compact('payment'));
    }
}
