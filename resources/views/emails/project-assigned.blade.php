<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Assigned - TREIS ADIUTOR</title>
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
                    
                    <!-- Header with Professional Gradient -->
                    <tr>
                        <td style="background-color: #3B82F6; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; font-size: 13px; color: #FFFFFF; letter-spacing: 0.05em;">
                                DIGITAL INNOVATION • INTELLIGENCE • EXCELLENCE
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                New Project Assignment
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                You've been assigned to work on <strong style="color: #0F172A;">"{{ $assignment->project->title ?? $assignment->project->serviceRequest->project_name }}"</strong>.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Project Details -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Assignment Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Project Title
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->project->title ?? $assignment->project->serviceRequest->project_name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Client
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->project->client->name ?? $assignment->project->serviceRequest->client->name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Your Role
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->role ?? 'Team Member' }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Assignment Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->assigned_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        @if($assignment->project->timeline_start)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Start Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->project->timeline_start->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($assignment->project->timeline_end)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Target Completion
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->project->timeline_end->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Project Manager
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $assignment->assignedBy->name }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            @if($assignment->project->description || $assignment->project->serviceRequest->project_description)
                            <!-- Project Description -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    {{ $assignment->project->description ?? $assignment->project->serviceRequest->project_description }}
                                </p>
                            </div>
                            @endif
                            
                            @if($assignment->responsibilities)
                            <!-- Responsibilities -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #F0FDF4; border-left: 3px solid #16A34A; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; font-weight: 500; color: #0F172A; margin-bottom: 8px;">Your Responsibilities:</p>
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    {{ $assignment->responsibilities }}
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
                            <a href="{{ route('adiutor.projects.show', $assignment->project->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s;">
                                Access Project Workspace
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Getting Started -->
                    <tr>
                        <td style="padding: 32px 40px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Getting Started
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Review Project Materials</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Familiarize yourself with requirements, documentation, and expectations</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Meet Your Team</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Connect with team members and establish communication channels</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">3</div>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Plan Your Approach</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Outline your initial approach and identify any questions needed</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0; vertical-align: top; width: 24px;">
                                        <div style="font-size: 14px; font-weight: 600; color: #3B82F6;">4</div>
                                    </td>
                                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Begin Contribution</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Start working on your assigned tasks and provide updates</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Questions? Contact our projects team at<br>
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