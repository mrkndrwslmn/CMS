<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed - TREIS ADIUTOR</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>

<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #3B82F6; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Main Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Payment Confirmed
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Thank you! Your {{ strtolower($payment->getPaymentTypeLabel()) }} of <strong>₱{{ number_format($payment->amount, 2) }}</strong> has been successfully received and processed.
                                @if($payment->isFullPayment())
                                Your project is now approved and ready to begin.
                                @elseif($payment->isDownpayment())
                                Your project can now begin development. The remaining balance will be due upon project completion.
                                @elseif($payment->isMilestonePayment())
                                Thank you for your milestone payment. Work will continue on the next phase of your project.
                                @endif
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Details Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Payment Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 140px;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Project Name</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $serviceRequest->project_name ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Payment Type</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $payment->getPaymentTypeLabel() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Amount Paid</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">₱{{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                </tr>
                                @if($serviceRequest->approved_budget && $payment->amount < $serviceRequest->approved_budget)
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Project Total</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #64748B;">₱{{ number_format($serviceRequest->approved_budget, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Remaining Balance</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #F59E0B; font-weight: 500;">₱{{ number_format($serviceRequest->approved_budget - $payment->amount, 2) }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Reference</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 14px; color: #1E293B;">{{ $payment->payment_reference ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Payment Date</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $payment->confirmed_at ? $payment->confirmed_at->format('M d, Y g:i A') : now()->format('M d, Y g:i A') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Next Steps -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                What Happens Next
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                @if($payment->isFullPayment())
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Project Assignment</div>
                                        <div style="font-size: 13px; color: #64748B;">Our team will assign dedicated professionals to your project</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Kickoff Meeting</div>
                                        <div style="font-size: 13px; color: #64748B;">Schedule a meeting to discuss requirements and timeline</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Project Development</div>
                                        <div style="font-size: 13px; color: #64748B;">Development begins and you'll receive regular progress updates</div>
                                    </td>
                                </tr>
                                @elseif($payment->isDownpayment())
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Project Initiation</div>
                                        <div style="font-size: 13px; color: #64748B;">Your project will begin development with the downpayment received</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Progress Updates</div>
                                        <div style="font-size: 13px; color: #64748B;">Receive regular updates as work progresses</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Final Payment</div>
                                        <div style="font-size: 13px; color: #64748B;">Remaining balance due upon project completion</div>
                                    </td>
                                </tr>
                                @elseif($payment->isMilestonePayment())
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Milestone Completion</div>
                                        <div style="font-size: 13px; color: #64748B;">Current milestone work will be completed and delivered</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Next Phase</div>
                                        <div style="font-size: 13px; color: #64748B;">Work continues on the next project phase</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Regular Updates</div>
                                        <div style="font-size: 13px; color: #64748B;">Receive progress notifications and deliverables</div>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Payment Processing</div>
                                        <div style="font-size: 13px; color: #64748B;">Your payment has been received and is being processed</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Team Notification</div>
                                        <div style="font-size: 13px; color: #64748B;">Our team has been notified and will take appropriate action</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Status Updates</div>
                                        <div style="font-size: 13px; color: #64748B;">You'll receive updates on your project status</div>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 32px 40px 0; text-align: center;">
                            <a href="{{ url('/dashboard') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                View Dashboard
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0; margin-top: 32px;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Questions about your payment?<br>
                                Contact us at <a href="mailto:support@treisadiutor.com" style="color: #3B82F6; text-decoration: none;">support@treisadiutor.com</a>
                            </p>
                            
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 11px; color: #CBD5E1; letter-spacing: 0.05em;">
                                    © 2025 TREIS ADIUTOR. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>