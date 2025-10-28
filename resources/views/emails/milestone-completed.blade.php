<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milestone Completed - TREIS ADIUTOR</title>
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
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 50%, #A7F3D0 100%); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 50%, #A7F3D0 100%); min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08), 0 8px 32px rgba(0, 0, 0, 0.03); border: 1px solid rgba(241, 245, 249, 0.9); overflow: hidden;">
                    
                    <!-- Header with Achievement Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba(16, 185, 129, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Achievement Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">🏆</div>
                            
                            <!-- Logo/Brand -->
                            <h1 style="margin: 0 0 8px; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Projects Department</p>
                        </td>
                    </tr>
                    
                    <!-- Milestone Achievement Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Milestone Completed! 🎉
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Excellent news! We've successfully completed milestone <strong style="color: #1E293B;">"{{ $milestone->title }}"</strong> for your project 
                                    <strong style="color: #059669;">"{{ $milestone->project->title ?? $milestone->project->serviceRequest->project_name }}"</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Milestone Achievement Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-radius: 16px; padding: 32px; border: 1px solid #BBF7D0; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #10B981, #059669);"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">🎯</span>
                                    </span>
                                    Milestone Achievement
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 20px;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Milestone</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 18px; font-weight: 700; color: #1E293B;">{{ $milestone->title }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Project</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $milestone->project->title ?? $milestone->project->serviceRequest->project_name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Started Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $milestone->start_date->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Completed Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $milestone->completed_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($milestone->due_date)
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Timeline Status</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                @if($milestone->completed_at <= $milestone->due_date)
                                                    <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">✅ On Schedule</span>
                                                @else
                                                    <span style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #FDE68A;">⏰ Extended Timeline</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($milestone->budget_allocation)
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Budget Allocation</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">₱{{ number_format($milestone->budget_allocation, 2) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Status</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">🏆 Completed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($milestone->completion_notes)
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0; margin-bottom: 16px;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #1E293B;">Completion Notes:</h4>
                                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-line;">{{ $milestone->completion_notes }}</p>
                                </div>
                                @endif
                                
                                @if($milestone->deliverables)
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0; margin-bottom: 16px;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #1E293B;">Delivered Items:</h4>
                                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-line;">{{ $milestone->deliverables }}</p>
                                </div>
                                @endif
                                
                                <div style="background: #DCFCE7; border: 1px solid #BBF7D0; border-radius: 8px; padding: 16px;">
                                    <p style="margin: 0; font-size: 14px; color: #065F46; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">🎉</span>
                                        <span><strong>Milestone Achieved!</strong> This milestone has been successfully completed and all deliverables are ready for your review.</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ route('client.milestones.show', $milestone->id) }}" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25), 0 4px 8px rgba(16, 185, 129, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                <span style="display: inline-block; margin-right: 8px;">📋</span>
                                Review Deliverables
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Project Progress Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0;">
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B; text-align: center;">
                                    Project Progress Update
                                </h3>
                                
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📊</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Review Completed Work</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">All milestone deliverables are available for your review and approval</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Provide Feedback</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Share your thoughts and feedback on the completed milestone</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">🚀</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Continue Forward</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">We're ready to move on to the next phase of your project</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="background: #F0F9FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                    <p style="margin: 0; font-size: 14px; color: #1D4ED8; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">⭐</span>
                                        <span><strong>Celebrating Success!</strong> Each completed milestone brings us closer to delivering your vision. Thank you for trusting us with your project!</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>Celebrating achievements with you,</strong><br>
                                <span style="background: linear-gradient(135deg, #10B981, #059669); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Projects Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    Milestone completion notification for "{{ $milestone->title }}". 
                                    <br>Another successful step in bringing your vision to life!
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