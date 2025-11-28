@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Payout Approved</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7; text-align: center;">Great news! Your payout request has been reviewed and approved. Payment processing is underway.</p>
        
        <!-- Payment Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Payment Details</p>
            
            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #BFDBFE;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Payout Number</p>
                            <p style="margin: 0; font-size: 15px; color: #1E3A8A; font-weight: 600;">{{ $payout->payout_number }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #BFDBFE;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Amount</p>
                            <p style="margin: 0; font-size: 24px; color: #3B82F6; font-weight: 700; line-height: 1.2;">₱{{ number_format($payout->amount, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #BFDBFE;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Period</p>
                            <p style="margin: 0; font-size: 14px; color: #1E3A8A; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #BFDBFE;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Payment Method</p>
                            <p style="margin: 0; font-size: 14px; color: #1E3A8A; font-weight: 500; text-transform: capitalize;">{{ $payout->payment_method }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #BFDBFE;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Approved By</p>
                            <p style="margin: 0; font-size: 14px; color: #1E3A8A; font-weight: 500;">{{ $approvedBy->fullName }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Approval Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1E3A8A; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y h:i A') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($payout->admin_notes)
            <!-- Admin Notes -->
            <div style="margin-top: 16px; background: #F0FDF4; border-left: 3px solid #10B981; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #065F46;">Admin Notes</p>
                <p style="margin: 0; font-size: 14px; color: #047857; line-height: 1.7; white-space: pre-line;">{{ $payout->admin_notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('adiutor.earnings.payouts.show', $payout->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Payout Details</a>
        </div>
        
        <!-- What Happens Next Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What Happens Next</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Payment Processing</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Your payment is being processed through {{ ucfirst($payout->payment_method) }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Transfer Timeline</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Expect to receive the payment within 1-3 business days</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Payment Confirmation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive a confirmation email once the payment is completed</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions about your payment? Contact us at<br>
                <a href="mailto:finance@treisadiutor.com" style="color: #3B82F6; text-decoration: none; font-weight: 500;">finance@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
