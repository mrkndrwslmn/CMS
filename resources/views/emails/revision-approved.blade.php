<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revision Approved - TREIS ADIUTOR</title>
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
                            
                            <!-- Approval Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">✅</div>
                            
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
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Revision Approved!
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Dear {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Valued Client' }}, great news! Your revision request for <strong style="color: #1E293B;">"{{ $revision->project->project_name }}"</strong> has been approved.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Revision Details Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #10B981, #059669);"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">📝</span>
                                    </span>
                                    Revision Details
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 20px;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Project</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; font-weight: 600; color: #1E293B;">{{ $revision->project->project_name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Revision Type</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">{{ $revision->revision_type ?? 'Standard Revision' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Approved Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $revision->approved_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($revision->estimated_completion)
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Expected Completion</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #059669; font-weight: 600;">{{ \Carbon\Carbon::parse($revision->estimated_completion)->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($revision->description)
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #1E293B;">Revision Description:</h4>
                                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">{{ $revision->description }}</p>
                                </div>
                                @endif
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
                                    What Happens Next?
                                </h3>
                                
                                <p style="margin: 0 0 24px; font-size: 16px; color: #475569; line-height: 1.6;">
                                    Our team will now begin implementing the approved revisions. You can track the progress in your client dashboard and we'll notify you upon completion.
                                </p>
                                
                                <div style="background: rgba(255, 255, 255, 0.8); border-radius: 12px; padding: 20px;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                                <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">1️⃣</span>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                                <strong style="color: #1E293B; font-weight: 600;">Revision Implementation</strong>
                                                <br><span style="color: #64748B; font-size: 14px;">Our team begins work on your approved changes</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                                <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">2️⃣</span>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                                <strong style="color: #1E293B; font-weight: 600;">Progress Updates</strong>
                                                <br><span style="color: #64748B; font-size: 14px;">Regular updates on implementation progress</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                                <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">3️⃣</span>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                                <strong style="color: #1E293B; font-weight: 600;">Revision Delivery</strong>
                                                <br><span style="color: #64748B; font-size: 14px;">Completed revisions delivered for your review</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ route('client.dashboard') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                <span style="display: inline-block; margin-right: 8px;">📊</span>
                                Track Progress
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Support Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; text-align: center;">
                                <h3 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B;">
                                    Questions About Your Revision?
                                </h3>
                                <p style="margin: 0 0 20px; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Our team is here to ensure your revision meets your exact requirements. Don't hesitate to reach out with any questions.
                                </p>
                                
                                <div style="background: #DCFCE7; border: 1px solid #BBF7D0; border-radius: 8px; padding: 16px;">
                                    <p style="margin: 0; font-size: 14px; color: #065F46; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">💬</span>
                                        <span><strong>Direct Communication:</strong> Use the project dashboard to communicate directly with your assigned team.</span>
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
                                <span style="background: linear-gradient(135deg, #10B981, #059669); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Projects Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    Revision approval notification for {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Valued Client' }}. 
                                    <br>We're committed to delivering exactly what you envision.
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