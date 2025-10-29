<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Service Request - TREIS ADIUTOR</title>
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
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); border: 1px solid #E2E8F0; overflow: hidden;">
                    
                    <!-- Header Section -->
                    <tr>
                        <td style="background-color: #3B82F6; padding: 48px 40px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                New Service Request
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 14px; font-weight: 500;">Request Management</p>
                        </td>
                    </tr>
                    
                    <!-- Message Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <p style="margin: 0; font-size: 16px; color: #0F172A; line-height: 1.6;">
                                A new service request has been submitted by <strong>{{ $serviceRequest->user->fullName }}</strong>
                                @if($isNewUser)
                                    <br><span style="display: inline-block; background-color: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-top: 8px;">NEW USER</span>
                                @endif
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Request Details Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Request Information
                            </h3>
                            
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 24px;">
                                <div style="margin-bottom: 20px;">
                                    <div style="font-size: 13px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Project Name</div>
                                    <div style="font-size: 16px; font-weight: 600; color: #0F172A;">{{ $serviceRequest->project_name }}</div>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <div style="font-size: 13px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Submitted By</div>
                                    <div style="font-size: 14px; color: #0F172A;">{{ $serviceRequest->user->fullName }}</div>
                                    <div style="font-size: 13px; color: #64748B;">{{ $serviceRequest->user->email }}</div>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <div style="font-size: 13px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Submitted Date</div>
                                    <div style="font-size: 14px; color: #0F172A;">{{ $serviceRequest->created_at->format('M d, Y \a\t g:i A') }}</div>
                                </div>
                                
                                <div>
                                    <div style="font-size: 13px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                                    <div style="font-size: 14px; color: #0F172A;">{{ ucfirst($serviceRequest->status) }}</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    @if($serviceRequest->project_description)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 20px;">
                                <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #0F172A;">Project Description</h4>
                                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-line;">{{ $serviceRequest->project_description }}</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    @if($serviceRequest->budget_range)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 16px;">
                                <p style="margin: 0; font-size: 14px; color: #1E40AF;"><strong>Budget Range:</strong> {{ $serviceRequest->budget_range }}</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    @if($serviceRequest->preferred_timeline)
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 16px;">
                                <p style="margin: 0; font-size: 14px; color: #1E40AF;"><strong>Preferred Timeline:</strong> {{ $serviceRequest->preferred_timeline }}</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Action Items Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Required Actions
                            </h3>
                            
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 24px;">
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Review Details</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Examine the request thoroughly and assess requirements
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
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Evaluate Feasibility</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Assess technical requirements and resource availability
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
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Take Action</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Approve, request more info, or decline through the admin panel
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
                            <a href="{{ route('admin.requests.show', $serviceRequest->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                View Full Request
                            </a>
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
                                Request Management Alert
                                <br>
                                <span style="color: #3B82F6;">{{ config('app.name') }} Admin Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                    Service request notification for "{{ $serviceRequest->project_name }}". 
                                    <br>This is an automated alert from your management system.
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
