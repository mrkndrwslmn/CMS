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
                                Thank you! Your payment has been successfully received and processed. Your project is now approved and ready to begin.
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
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Amount</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">₱{{ number_format($serviceRequest->approved_budget ?? 0, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Reference</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 14px; color: #1E293B;">{{ $serviceRequest->payment_reference ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Payment Date</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ now()->format('M d, Y') }}</span>
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
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Regular Updates</div>
                                        <div style="font-size: 13px; color: #64748B;">Receive progress updates and milestone notifications</div>
                                    </td>
                                </tr>
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