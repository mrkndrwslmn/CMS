<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Rescheduled - TREIS ADIUTOR</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #F59E0B; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; font-size: 15px; color: #FEF3C7; font-weight: 500;">
                                Meeting Rescheduled
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Main Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                New Time Proposed for Your Meeting
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                The admin has proposed a new time for your meeting <strong style="color: #0F172A;">"{{ $meeting->title }}"</strong>. Please review and approve or decline.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Proposed Date/Time Box -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <div style="background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 20px; border-radius: 4px; text-align: center;">
                                <p style="margin: 0 0 8px; font-size: 13px; color: #92400E; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Proposed New Time
                                </p>
                                <p style="margin: 0; font-size: 24px; color: #78350F; font-weight: 700;">
                                    {{ \Carbon\Carbon::parse($meeting->rescheduled_date)->format('F d, Y') }}
                                </p>
                                <p style="margin: 4px 0 0; font-size: 20px; color: #92400E; font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($meeting->rescheduled_time, 'H:i')->format('g:i A') }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    @if($meeting->admin_notes)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                                Admin Notes
                            </h3>
                            <div style="background-color: #F8FAFC; border-left: 4px solid #3B82F6; padding: 16px; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                    {{ $meeting->admin_notes }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Action Buttons -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ config('app.url') }}/client/meetings/{{ $meeting->id }}/approve-reschedule" style="display: inline-block; padding: 14px 28px; background-color: #10B981; color: #FFFFFF; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; letter-spacing: 0.025em; margin-right: 12px;">
                                Approve New Time
                            </a>
                            <a href="{{ config('app.url') }}/client/meetings/{{ $meeting->id }}/reject-reschedule" style="display: inline-block; padding: 14px 28px; background-color: #6B7280; color: #FFFFFF; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; letter-spacing: 0.025em;">
                                Decline
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
