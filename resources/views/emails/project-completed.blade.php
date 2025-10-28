<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Completed - TREIS ADIUTOR</title>
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
                            <p style="margin: 0; font-size: 13px; color: rgba(255, 255, 255, 0.9); letter-spacing: 0.05em;">
                                DIGITAL INNOVATION • INTELLIGENCE • EXCELLENCE
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Main Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Project Completed Successfully
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Your project has been completed and is ready for delivery. All deliverables are now available in your dashboard.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Project Summary Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Project Summary
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 140px;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Project Name</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $project->project_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Service Type</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $project->service_type ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Completed Date</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $project->completed_at ? $project->completed_at->format('M d, Y') : 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <label style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Duration</label>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <span style="font-size: 15px; color: #1E293B; font-weight: 500;">{{ $project->completed_at ? $project->created_at->diffInDays($project->completed_at) : 'N/A' }} days</span>
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
                    
                    <!-- Deliverables -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Your Deliverables
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Final Documentation</div>
                                        <div style="font-size: 13px; color: #64748B;">Complete project documentation and user guides</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 12px 0 12px 16px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Source Files & Assets</div>
                                        <div style="font-size: 13px; color: #64748B;">All project files and digital assets</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 12px 0; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Support & Maintenance</div>
                                        <div style="font-size: 13px; color: #64748B;">30-day free support and maintenance included</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 32px 40px 0; text-align: center;">
                            <a href="{{ url('/dashboard') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                Access Your Deliverables
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0; margin-top: 32px;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Thank you for working with us. We appreciate your business.<br>
                                For any questions, contact us at <a href="mailto:support@treisadiutor.com" style="color: #3B82F6; text-decoration: none;">support@treisadiutor.com</a>
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