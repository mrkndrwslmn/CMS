@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Payment Successfully Completed</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7; text-align: center;">Your payment has been processed and sent. You should receive it within the expected timeframe.</p>
        
        <!-- Payment Summary Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Payment Summary</p>
            
            <div style="background: #ECFDF5; border: 1px solid #A7F3D0; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Payout Number</p>
                            <p style="margin: 0; font-size: 15px; color: #064E3B; font-weight: 600;">{{ $payout->payout_number }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Amount Paid</p>
                            <p style="margin: 0; font-size: 28px; color: #10B981; font-weight: 700; line-height: 1.2;">₱{{ number_format($payout->amount, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Period</p>
                            <p style="margin: 0; font-size: 14px; color: #064E3B; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Payment Method</p>
                            <p style="margin: 0; font-size: 14px; color: #064E3B; font-weight: 500; text-transform: capitalize;">{{ $payout->payment_method }}</p>
                        </td>
                    </tr>
                    @if($payout->reference_number)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Reference Number</p>
                            <p style="margin: 0; font-size: 14px; color: #064E3B; font-weight: 600; font-family: 'Courier New', monospace;">{{ $payout->reference_number }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #A7F3D0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Processed By</p>
                            <p style="margin: 0; font-size: 14px; color: #064E3B; font-weight: 500;">{{ $processedBy->fullName }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">Payment Date</p>
                            <p style="margin: 0; font-size: 14px; color: #064E3B; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y h:i A') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Earnings Breakdown Section -->
        <div style="margin: 0 0 24px 0; padding: 24px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Earnings Breakdown</p>
            
            <table style="width: 100%; border-collapse: collapse; background: #F9FAFB; border: 1px solid #E5E7EB;">
                <thead>
                    <tr style="background: #F3F4F6; border-bottom: 1px solid #E5E7EB;">
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-align: left; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-align: right; text-transform: uppercase; letter-spacing: 0.5px;">Hours</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-align: right; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payout->items as $item)
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td style="padding: 12px 16px; font-size: 13px; color: #1F2937;">{{ $item->description }}</td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #6B7280; text-align: right;">{{ number_format($item->hours, 2) }}</td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #1F2937; font-weight: 600; text-align: right;">₱{{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #F3F4F6; border-top: 2px solid #E5E7EB;">
                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #1F2937;">Total</td>
                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #1F2937; text-align: right;">{{ number_format($payout->items->sum('hours'), 2) }}</td>
                        <td style="padding: 12px 16px; font-size: 16px; font-weight: 700; color: #10B981; text-align: right;">₱{{ number_format($payout->amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('adiutor.earnings.payouts.show', $payout->id) }}" style="display: inline-block; background: #10B981; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View Payment Receipt</a>
            <a href="{{ route('adiutor.earnings.index') }}" style="display: inline-block; background: #6B7280; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View Earnings Dashboard</a>
        </div>
        
        <!-- Important Information Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Important Information</p>
            
            <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; margin: 0 0 16px 0;">
                <p style="margin: 0; font-size: 13px; color: #78350F; line-height: 1.7;">
                    <strong style="font-weight: 600; color: #92400E;">Processing Time:</strong> Depending on your payment method, it may take 1-3 business days for the funds to appear in your account.
                </p>
            </div>
            
            <div style="background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 13px; color: #1E40AF; line-height: 1.7;">
                    <strong style="font-weight: 600;">Keep This Email:</strong> Save this confirmation email for your records. The reference number may be needed for any payment inquiries.
                </p>
            </div>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions about your payment? Contact us at<br>
                <a href="mailto:finance@treisadiutor.com" style="color: #10B981; text-decoration: none; font-weight: 500;">finance@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
