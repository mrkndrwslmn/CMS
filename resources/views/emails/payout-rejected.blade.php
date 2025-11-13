<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Request Declined - TREIS ADIUTOR</title>
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
                        <td style="background-color: #EF4444; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Alert Icon -->
                    <tr>
                        <td style="padding: 40px 40px 0; text-align: center;">
                            <div style="display: inline-block; background-color: #FEE2E2; border-radius: 50%; width: 80px; height: 80px; line-height: 80px;">
                                <span style="font-size: 40px; color: #EF4444;">⚠</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 24px 40px 32px; text-align: center;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Payout Request Declined
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                We're unable to process your payout request at this time. Please review the reason below and take appropriate action.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Payout Details -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Request Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #FEF2F2; border: 1px solid #FECACA; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Payout Number
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $payout->payout_number }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Amount
                                            </label>
                                            <div style="font-size: 20px; color: #0F172A; font-weight: 700;">
                                                ₱{{ number_format($payout->amount, 2) }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Period
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Reviewed By
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $rejectedBy->fullName }}
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Review Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            @if($payout->rejection_reason)
                            <!-- Rejection Reason -->
                            <div style="margin-top: 16px; padding: 16px 20px; background-color: #FEF2F2; border: 2px solid #EF4444; border-radius: 6px;">
                                <p style="margin: 0; font-size: 13px; font-weight: 600; color: #DC2626; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Reason for Decline:</p>
                                <p style="margin: 0; font-size: 14px; color: #0F172A; line-height: 1.5; white-space: pre-line;">
                                    {{ $payout->rejection_reason }}
                                </p>
                            </div>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 32px 40px; text-align: center;">
                            <a href="{{ route('adiutor.earnings.index') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s; margin-right: 8px;">
                                View Earnings Dashboard
                            </a>
                            <a href="{{ route('adiutor.earnings.payouts.show', $payout->id) }}" style="display: inline-block; background-color: #64748B; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s;">
                                View Request Details
                            </a>
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
                        <td style="padding: 32px 40px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                What You Can Do
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #EF4444;">1</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Review the Reason</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Carefully read the decline reason to understand what needs to be addressed</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #EF4444;">2</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Contact Finance Team</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Reach out if you need clarification or have questions about the decision</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #EF4444;">3</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Make Corrections</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Address the issues mentioned and ensure all requirements are met</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #EF4444;">4</div>
                                    </td>
                                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Submit New Request</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Once corrected, you can submit a new payout request</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Need assistance? Contact our finance team at<br>
                                <a href="mailto:finance@treisadiutor.com" style="color: #EF4444; text-decoration: none;">finance@treisadiutor.com</a>
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
