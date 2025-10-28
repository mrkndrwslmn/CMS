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
                                Revision Approved
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Your revision request for <strong style="color: #0F172A;">"{{ $revision->project->project_name }}"</strong> has been approved.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Revision Details -->
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
                                                Approved Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revision->approved_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        @if($revision->estimated_completion)
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Expected Completion
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($revision->estimated_completion)->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            
                            @if($revision->description)
                            <!-- Description -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;">
                                    {{ $revision->description }}
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
                    
                    <!-- Next Steps Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                What Happens Next
                            </h3>
                            
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 24px;">
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Revision Implementation</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Our team will now begin implementing the approved changes.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Progress Updates</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                You'll receive regular updates on implementation progress through your dashboard.
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
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Completion & Delivery</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                We'll notify you when your revisions are ready for review.
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
                                Track Progress in Dashboard
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Support Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Questions or Concerns?
                            </h3>
                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                Our team is here to help. You can communicate directly with your assigned team through your project dashboard, or reply to this email with any questions.
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
                                    Revision approved notification for {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Client' }}. 
                                    <br>Thank you for your continued trust in our services.
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