@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #DC2626; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Payout Request Declined</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7; text-align: center;">We're unable to process your payout request at this time. Please review the reason below and take appropriate action.</p>
        
        <!-- Request Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Request Details</p>
            
            <div style="background: #FEF2F2; border: 1px solid #FECACA; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #FCA5A5;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Payout Number</p>
                            <p style="margin: 0; font-size: 15px; color: #7F1D1D; font-weight: 600;">{{ $payout->payout_number }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #FCA5A5;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Amount</p>
                            <p style="margin: 0; font-size: 20px; color: #7F1D1D; font-weight: 700; line-height: 1.2;">₱{{ number_format($payout->amount, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #FCA5A5;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Period</p>
                            <p style="margin: 0; font-size: 14px; color: #7F1D1D; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #FCA5A5;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Reviewed By</p>
                            <p style="margin: 0; font-size: 14px; color: #7F1D1D; font-weight: 500;">{{ $rejectedBy->fullName }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Review Date</p>
                            <p style="margin: 0; font-size: 14px; color: #7F1D1D; font-weight: 500;">{{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y h:i A') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($payout->rejection_reason)
            <!-- Rejection Reason -->
            <div style="margin-top: 16px; background: #FEF2F2; border-left: 3px solid #DC2626; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Reason for Decline</p>
                <p style="margin: 0; font-size: 14px; color: #7F1D1D; line-height: 1.7; white-space: pre-line;">{{ $payout->rejection_reason }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('adiutor.earnings.index') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View Earnings Dashboard</a>
            <a href="{{ route('adiutor.earnings.payouts.show', $payout->id) }}" style="display: inline-block; background: #6B7280; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View Request Details</a>
        </div>
        
        <!-- What You Can Do Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What You Can Do</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #FEE2E2; color: #DC2626; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #FCA5A5; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Review the Reason</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Carefully read the decline reason to understand what needs to be addressed</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #FEE2E2; color: #DC2626; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #FCA5A5; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Contact Finance Team</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Reach out if you need clarification or have questions about the decision</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #FEE2E2; color: #DC2626; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #FCA5A5; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Make Corrections</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Address the issues mentioned and ensure all requirements are met</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #FEE2E2; color: #DC2626; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #FCA5A5; border-radius: 50%;">4</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Submit New Request</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Once corrected, you can submit a new payout request</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Need assistance? Contact our finance team at<br>
                <a href="mailto:finance@treisadiutor.com" style="color: #DC2626; text-decoration: none; font-weight: 500;">finance@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
