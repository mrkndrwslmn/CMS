@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Payment Confirmed</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">
            Thank you! Your {{ strtolower($payment->getPaymentTypeLabel()) }} of <strong style="color: #1F2937;">₱{{ number_format($payment->amount, 2) }}</strong> has been successfully received and processed.
            @if($payment->isFullPayment())
            Your project is now approved and ready to begin.
            @elseif($payment->isDownpayment())
            Your project can now begin development. The remaining balance will be due upon project completion.
            @elseif($payment->isMilestonePayment())
            Thank you for your milestone payment. Work will continue on the next phase of your project.
            @endif
        </p>
        
        <!-- Payment Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Payment Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; width: 140px; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Name</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $serviceRequest->project_name ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Payment Type</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $payment->getPaymentTypeLabel() }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Amount Paid</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">₱{{ number_format($payment->amount, 2) }}</p>
                        </td>
                    </tr>
                    @if($serviceRequest->approved_budget && $payment->amount < $serviceRequest->approved_budget)
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Total</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 14px; color: #6B7280;">₱{{ number_format($serviceRequest->approved_budget, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Remaining Balance</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 14px; color: #F59E0B; font-weight: 500;">₱{{ number_format($serviceRequest->approved_budget - $payment->amount, 2) }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Reference</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-family: 'Courier New', monospace; font-size: 14px; color: #1F2937;">{{ $payment->payment_reference ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0 0 0; vertical-align: top;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Payment Date</p>
                        </td>
                        <td style="padding: 12px 0 0 0; vertical-align: top;">
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $payment->confirmed_at ? $payment->confirmed_at->format('M d, Y g:i A') : now()->format('M d, Y g:i A') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- What Happens Next Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What Happens Next</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                @if($payment->isFullPayment())
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Project Assignment</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team will assign dedicated professionals to your project</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Kickoff Meeting</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Schedule a meeting to discuss requirements and timeline</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Project Development</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Development begins and you'll receive regular progress updates</p>
                    </td>
                </tr>
                @elseif($payment->isDownpayment())
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Project Initiation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Your project will begin development with the downpayment received</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Progress Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Receive regular updates as work progresses</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Final Payment</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Remaining balance due upon project completion</p>
                    </td>
                </tr>
                @elseif($payment->isMilestonePayment())
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Milestone Completion</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Current milestone work will be completed and delivered</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Next Phase</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Work continues on the next project phase</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Regular Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Receive progress notifications and deliverables</p>
                    </td>
                </tr>
                @else
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Payment Processing</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Your payment has been received and is being processed</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Team Notification</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team has been notified and will take appropriate action</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Status Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive updates on your project status</p>
                    </td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0 0 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ url('/dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Dashboard</a>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions about your payment?<br>
                Contact us at <a href="mailto:support@treisadiutor.com" style="color: #3B82F6; text-decoration: none; font-weight: 500;">support@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
