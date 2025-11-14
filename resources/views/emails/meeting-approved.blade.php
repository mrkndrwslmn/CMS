<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Approved - TREIS ADIUTOR</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10B981; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; font-size: 15px; color: #D1FAE5; font-weight: 500;">
                                Meeting Approved ✓
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Main Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Your Meeting Has Been Approved
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Great news! Your meeting request for <strong style="color: #0F172A;">"{{ $meeting->title }}"</strong> has been approved and scheduled.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Scheduled Date/Time Box -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 20px; border-radius: 4px; text-align: center;">
                                <p style="margin: 0 0 8px; font-size: 13px; color: #047857; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Scheduled For
                                </p>
                                <p style="margin: 0; font-size: 24px; color: #065F46; font-weight: 700;">
                                    {{ \Carbon\Carbon::parse($meeting->scheduled_date)->format('F d, Y') }}
                                </p>
                                <p style="margin: 4px 0 0; font-size: 20px; color: #047857; font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($meeting->scheduled_time, 'H:i')->format('g:i A') }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Zoom Link -->
                    @if($meeting->zoom_join_url)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #EFF6FF; border: 2px solid #3B82F6; border-radius: 8px; padding: 24px; text-align: center;">
                                <h3 style="margin: 0 0 16px; font-size: 16px; font-weight: 600; color: #1E40AF;">
                                    📹 Zoom Meeting Link
                                </h3>
                                <a href="{{ $meeting->zoom_join_url }}" style="display: inline-block; padding: 14px 32px; background-color: #2563EB; color: #FFFFFF; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; letter-spacing: 0.025em; margin-bottom: 16px;">
                                    Join Meeting
                                </a>
                                <p style="margin: 12px 0 0; font-size: 13px; color: #1E40AF;">
                                    <strong>Meeting ID:</strong> {{ $meeting->zoom_meeting_id }}
                                </p>
                                @if($meeting->zoom_password)
                                <p style="margin: 4px 0 0; font-size: 13px; color: #1E40AF;">
                                    <strong>Password:</strong> {{ $meeting->zoom_password }}
                                </p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Meeting Details -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 16px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                                Meeting Details
                            </h3>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #64748B;">
                                        <strong style="color: #475569;">Project:</strong>
                                    </td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #0F172A; text-align: right;">
                                        {{ $meeting->project->title }}
                                    </td>
                                </tr>
                                @if($meeting->description)
                                <tr>
                                    <td colspan="2" style="padding: 16px 0 0;">
                                        <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                            {{ $meeting->description }}
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ config('app.url') }}/client/projects/{{ $meeting->project_id }}#meetings" style="display: inline-block; padding: 14px 32px; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; letter-spacing: 0.025em;">
                                View Project
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; text-align: center; background-color: #0F172A;">
                            <p style="margin: 0 0 8px; font-size: 13px; color: #94A3B8;">
                                © {{ date('Y') }} TREIS ADIUTOR. All rights reserved.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #64748B;">
                                This is an automated message. Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
