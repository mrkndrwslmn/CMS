<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Request Declined - TREIS ADIUTOR</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #EF4444; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Main Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Meeting Request Could Not Be Approved
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                We regret to inform you that your meeting request for <strong style="color: #0F172A;">"{{ $meeting->title }}"</strong> could not be approved at this time.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Meeting Details -->
                    <tr>
                        <td style="padding: 32px 40px;">
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
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #64748B;">
                                        <strong style="color: #475569;">Requested Date:</strong>
                                    </td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #0F172A; text-align: right;">
                                        {{ \Carbon\Carbon::parse($meeting->requested_date)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($meeting->requested_time, 'H:i')->format('g:i A') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    @if($meeting->admin_notes)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                                Reason
                            </h3>
                            <div style="background-color: #FEF2F2; border-left: 4px solid: #EF4444; padding: 16px; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #7F1D1D; line-height: 1.6;">
                                    {{ $meeting->admin_notes }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Next Steps -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 20px;">
                                <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #475569;">
                                    What's Next?
                                </h3>
                                <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                    You can submit a new meeting request with alternative dates, or contact support for assistance.
                                </p>
                            </div>
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
