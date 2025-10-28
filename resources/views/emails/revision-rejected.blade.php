<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revision Feedback - TREIS ADIUTOR</title>
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
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); border: none; overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #3B82F6; padding: 48px 40px 40px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em;">
                                Revision Feedback
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px; font-weight: 500;">
                                Updates Needed
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #0F172A;">
                                Revision Requires Updates
                            </h2>
                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                Dear {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Valued Client' }}, we've received feedback on your revision for <strong style="color: #0F172A;">"{{ $revision->project->project_name }}"</strong> that requires some adjustments.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Revision Details Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Revision Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Project
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revision->project->project_name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Revision Type
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revision->revision_type ?? 'Standard Revision' }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Feedback Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revision->rejected_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Status
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                Needs Revision
                                            </div>
                                        </div>
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
                    
                    <!-- Feedback Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Feedback & Required Changes
                            </h3>
                            
                            @if($revision->rejection_reason)
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 20px 24px; margin-bottom: 16px;">
                                <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #0F172A;">Detailed Feedback:</h4>
                                <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6; white-space: pre-line;">{{ $revision->rejection_reason }}</p>
                            </div>
                            @endif
                            
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 24px;">
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Review the Feedback Carefully</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Understand the specific changes and improvements needed
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Submit Updated Revision</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Address the feedback points and resubmit your improved revision
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">3</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Discuss if Needed</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Contact us directly if you need any clarification on the feedback
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <a href="{{ route('client.dashboard') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                View Project Details
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Support Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Need Help?
                            </h3>
                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                We're committed to helping you achieve your vision. Use your project dashboard to communicate directly with your team or reply to this email with any questions about the feedback.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 14px; font-weight: 500; color: #0F172A;">
                                Best regards,<br>
                                <span style="color: #3B82F6;">{{ config('app.name') }} Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                    Revision feedback notification for {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Client' }}. 
                                    <br>We're committed to delivering excellence with your feedback.
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