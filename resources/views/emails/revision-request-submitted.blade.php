<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revision Request Submitted - TREIS ADIUTOR</title>
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
                                Revision Request Submitted
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px; font-weight: 500;">
                                Request Received
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #0F172A;">
                                Revision Request Submitted
                            </h2>
                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                A revision request has been submitted for your project <strong style="color: #0F172A;">"{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}"</strong> and has been received by our team for review.
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
                                Revision Request Details
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Project
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Request Type
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                @if($revisionRequest->type === 'minor')
                                                    Minor Revision
                                                @elseif($revisionRequest->type === 'major')
                                                    Major Revision
                                                @else
                                                    General Revision
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($revisionRequest->priority)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Priority
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                @if($revisionRequest->priority === 'high')
                                                    High Priority
                                                @elseif($revisionRequest->priority === 'medium')
                                                    Medium Priority
                                                @else
                                                    Low Priority
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Submitted By
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->requestedBy->name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Request Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->created_at->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                        
                                        @if($revisionRequest->deadline)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Requested Deadline
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->deadline->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Status
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                Under Review
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            @if($revisionRequest->description)
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    <strong>Description:</strong><br>{{ $revisionRequest->description }}
                                </p>
                            </div>
                            @endif
                            
                            @if($revisionRequest->specific_requirements)
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                    <strong>Specific Requirements:</strong><br>{{ $revisionRequest->specific_requirements }}
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
                        <td style="padding: 32px 40px; text-align: center;">
                            <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                Track Revision Status
                            </a>
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
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Initial Review</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Our team will review your revision request and assess the scope and requirements
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
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Timeline Estimation</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                You'll receive an estimated timeline and any additional information or clarifications needed
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">3</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Implementation</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Once approved, our team will begin implementing the requested revisions
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">4</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Completion & Review</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                You'll be notified when revisions are complete and ready for your final review
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
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 14px; font-weight: 500; color: #0F172A;">
                                Committed to your satisfaction,<br>
                                <span style="color: #3B82F6;">{{ config('app.name') }} Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                    Revision request confirmation for "{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}". 
                                    <br>We're dedicated to ensuring your project meets your exact expectations.
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