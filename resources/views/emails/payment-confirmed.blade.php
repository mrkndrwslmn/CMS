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
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%); min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.08), 0 8px 32px rgba(0, 0, 0, 0.03); border: 1px solid rgba(241, 245, 249, 0.9); overflow: hidden;">
                    
                    <!-- Header with Success Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba(6, 182, 212, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Success Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">✅</div>
                            
                            <!-- Logo/Brand -->
                            <h1 style="margin: 0 0 8px; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Billing Department</p>
                        </td>
                    </tr>
                    
                    <!-- Success Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Payment Confirmed!
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Dear {{ $serviceRequest->client->firstName }}, thank you! Your payment for project <strong style="color: #1E293B;">"{{ $serviceRequest->project_name }}"</strong> has been successfully confirmed.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Payment Details Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #10B981, #059669);"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">💳</span>
                                    </span>
                                    Payment Details
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Amount</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 24px; font-weight: 700; color: #10B981;">${{ number_format($serviceRequest->approved_budget, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Reference</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; background: #F8FAFC; padding: 6px 10px; border-radius: 6px; font-size: 14px; color: #1E293B; border: 1px solid #E2E8F0;">{{ $serviceRequest->payment_reference }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Confirmed Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $serviceRequest->payment_confirmed_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Next Steps Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 16px; padding: 32px; border: 1px solid #BFDBFE; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #3B82F6, #06B6D4);"></div>
                                
                                <h3 style="margin: 0 0 20px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">🚀</span>
                                    </span>
                                    What's Next?
                                </h3>
                                
                                <p style="margin: 0 0 24px; font-size: 16px; color: #475569; line-height: 1.6;">
                                    Your project is now in progress! Our team will begin working on your project immediately. You can track the progress and updates in your client dashboard.
                                </p>
                                
                                <div style="background: #DCFCE7; border: 1px solid #BBF7D0; border-radius: 8px; padding: 16px;">
                                    <p style="margin: 0; font-size: 14px; color: #065F46; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">ℹ️</span>
                                        <span><strong>Stay Updated:</strong> We'll keep you informed about progress and notify you of any important milestones.</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ route('client.dashboard') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                <span style="display: inline-block; margin-right: 8px;">📊</span>
                                View Dashboard
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Support Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; text-align: center;">
                                <h3 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B;">
                                    Need Assistance?
                                </h3>
                                <p style="margin: 0 0 20px; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    If you have any questions about your payment or project, please don't hesitate to contact us.
                                </p>
                                
                                <div style="display: inline-block; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; text-align: left;">
                                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #06B6D4, #22D3EE); border-radius: 6px; margin-right: 12px; text-align: center; line-height: 24px; color: #FFFFFF; font-size: 12px;">📧</span>
                                        <span style="color: #475569; font-size: 14px; font-weight: 500;">Email Support</span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 6px; margin-right: 12px; text-align: center; line-height: 24px; color: #FFFFFF; font-size: 12px;">💬</span>
                                        <span style="color: #475569; font-size: 14px; font-weight: 500;">Live Chat Available</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>Thanks,</strong><br>
                                <span style="background: linear-gradient(135deg, #10B981, #059669); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Billing Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    This payment confirmation was sent to {{ $serviceRequest->client->firstName }}. 
                                    <br>For billing inquiries, please contact our billing department.
                                    <br><br>
                                    <span style="color: #64748B;">Digital Innovation • Intelligence • Excellence</span>
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