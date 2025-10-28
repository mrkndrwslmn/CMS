<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Request Approved - TREIS ADIUTOR</title>
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
                    
                    <!-- Header with Approval Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba(6, 182, 212, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Approval Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">🎉</div>
                            
                            <!-- Logo/Brand -->
                            <h1 style="margin: 0 0 8px; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Projects Department</p>
                        </td>
                    </tr>
                    
                    <!-- Approval Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Project Request Approved!
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Dear {{ $serviceRequest->client->fullName ?? $serviceRequest->client->full_name ?? 'Valued Client' }}, great news! Your project request <strong style="color: #1E293B;">"{{ $serviceRequest->project_name }}"</strong> has been approved.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Project Details Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #3B82F6, #06B6D4);"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">📋</span>
                                    </span>
                                    Project Details
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Project Name</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; font-weight: 600; color: #1E293B;">{{ $serviceRequest->project_name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Service Type</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="background: linear-gradient(135deg, #EFF6FF, #DBEAFE); color: #1D4ED8; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BFDBFE;">{{ $serviceRequest->service_type }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Budget</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 24px; font-weight: 700; color: #059669;">${{ number_format($serviceRequest->approved_budget, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Payment Due</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #DC2626; font-weight: 600;">{{ \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') }}</span>
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
                            <div style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-radius: 16px; padding: 32px; border: 1px solid #F59E0B; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #F59E0B, #D97706);"></div>
                                
                                <h3 style="margin: 0 0 20px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #92400E; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #F59E0B, #D97706); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">⚡</span>
                                    </span>
                                    Next Steps
                                </h3>
                                
                                <p style="margin: 0 0 20px; font-size: 16px; color: #92400E; line-height: 1.6; font-weight: 500;">
                                    To proceed with your project, please complete the payment in your request details.
                                </p>
                                
                                <div style="background: rgba(255, 255, 255, 0.8); border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                                    <div style="text-align: center; margin-bottom: 16px;">
                                        <div style="font-size: 14px; color: #92400E; font-weight: 600; margin-bottom: 4px;">AMOUNT DUE</div>
                                        <div style="font-size: 32px; font-weight: 700; color: #059669;">${{ number_format($serviceRequest->approved_budget, 2) }}</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #92400E; font-weight: 600; margin-bottom: 4px;">DUE DATE</div>
                                        <div style="font-size: 18px; font-weight: 600; color: #DC2626;">{{ \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                
                                @if($serviceRequest->payment_instructions)
                                <div style="background: rgba(255, 255, 255, 0.8); border-radius: 12px; padding: 20px;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #92400E;">Payment Instructions:</h4>
                                    <p style="margin: 0; font-size: 14px; color: #92400E; line-height: 1.5;">{{ $serviceRequest->payment_instructions }}</p>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ url('/client/requests') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                <span style="display: inline-block; margin-right: 8px;">📁</span>
                                View Your Requests
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Project Timeline Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0;">
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B; text-align: center;">
                                    What Happens Next?
                                </h3>
                                
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #F59E0B, #D97706); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">1️⃣</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Complete Payment</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Process your payment by {{ \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">2️⃣</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Payment Confirmation</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Receive confirmation and project kick-off details</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">3️⃣</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Project Begins</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Our team starts working on your project immediately</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                    <p style="margin: 0; font-size: 14px; color: #1D4ED8; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">💡</span>
                                        <span><strong>Pro Tip:</strong> Once payment is confirmed, we begin working immediately. Track progress in your dashboard!</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>Thanks,</strong><br>
                                <span style="background: linear-gradient(135deg, #3B82F6, #2563EB); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Projects Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    This project approval was sent to {{ $serviceRequest->client->fullName ?? $serviceRequest->client->full_name ?? 'Valued Client' }}. 
                                    <br>If you have any questions, please don't hesitate to contact us.
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
