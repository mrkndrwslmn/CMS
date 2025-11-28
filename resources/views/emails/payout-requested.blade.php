@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">New Payout Request</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;"><strong style="color: #1F2937;">{{ $adiutor->fullName }}</strong> has submitted a payout request that requires your review.</p>
        
        <!-- Request Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Request Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Payout Number</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $payout->payout_number }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Adiutor</p>
                            <p style="margin: 0 0 4px 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $adiutor->fullName }}</p>
                            <p style="margin: 0; font-size: 13px; color: #6B7280;">{{ $adiutor->email }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Amount</p>
                            <p style="margin: 0; font-size: 24px; color: #10B981; font-weight: 700; line-height: 1.2;">₱{{ number_format($payout->amount, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Period</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Payment Method</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500; text-transform: capitalize;">{{ $payout->payment_method }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Total Hours</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ number_format($payout->items->sum('hours'), 2) }} hours</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($payout->notes)
            <!-- Additional Notes -->
            <div style="margin-top: 16px; background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #92400E;">Adiutor Notes</p>
                <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.7; white-space: pre-line;">{{ $payout->notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('admin.payouts.show', $payout->id) }}" style="display: inline-block; background: #10B981; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Review Payout Request</a>
        </div>
        
        <!-- Review Checklist Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Review Checklist</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #D1FAE5; color: #10B981; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #A7F3D0; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Verify Time Entries</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Review all time entries included in this payout request</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #D1FAE5; color: #10B981; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #A7F3D0; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Check Payment Details</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Confirm payment method and account information</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #D1FAE5; color: #10B981; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #A7F3D0; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Validate Calculations</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Ensure amounts are calculated correctly based on rates</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #D1FAE5; color: #10B981; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #A7F3D0; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Approve or Reject</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Take action and notify the adiutor of your decision</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions? Contact the finance team at<br>
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
