<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milestone Started - TREIS ADIUTOR</title>
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
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Milestone Started
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                We've officially started working on <strong style="color: #0F172A;">"{{ $milestone->title }}"</strong> for your project.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Milestone Details -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Milestone Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Milestone Title
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $milestone->title }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Project
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $milestone->project->title ?? $milestone->project->serviceRequest->project_name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Start Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $milestone->start_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        @if($milestone->due_date)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Target Completion
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $milestone->due_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($milestone->budget_allocation)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Budget Allocation
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                ₱{{ number_format($milestone->budget_allocation, 2) }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Status
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                In Progress
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            @if($milestone->description)
                            <!-- Description -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    {{ $milestone->description }}
                                </p>
                            </div>
                            @endif
                            
                            @if($milestone->deliverables)
                            <!-- Expected Deliverables -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #F0FDF4; border-left: 3px solid #16A34A; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; font-weight: 500; color: #0F172A; margin-bottom: 8px;">Expected Deliverables:</p>
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    {{ $milestone->deliverables }}
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
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ route('client.milestones.show', $milestone->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s;">
                                Track Milestone Progress
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- What to Expect -->
                    <tr>
                        <td style="padding: 32px 40px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                What to Expect
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Regular Progress Updates</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">You'll receive periodic updates on milestone progress and key developments</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Communication Touchpoints</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Our team will reach out for any clarifications or feedback needed</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Milestone Completion</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">You'll be notified immediately when the milestone is completed and ready for review</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Questions? Contact our team at<br>
                                <a href="mailto:projects@treisadiutor.com" style="color: #3B82F6; text-decoration: none;">projects@treisadiutor.com</a>
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