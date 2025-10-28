<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revision Request Update - TREIS ADIUTOR</title>
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
                                Revision Request 
                                @if($revisionRequest->status === 'approved')
                                    Approved
                                @elseif($revisionRequest->status === 'rejected')
                                    Declined
                                @elseif($revisionRequest->status === 'in_progress')
                                    In Progress
                                @elseif($revisionRequest->status === 'completed')
                                    Completed
                                @else
                                    Updated
                                @endif
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px; font-weight: 500;">
                                Status Update
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #0F172A;">
                                Your revision request has been reviewed
                            </h2>
                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                Your revision request for <strong style="color: #0F172A;">"{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}"</strong> 
                                @if($revisionRequest->status === 'approved')
                                    has been <strong>approved</strong> and work will begin shortly.
                                @elseif($revisionRequest->status === 'rejected')
                                    has been <strong>declined</strong>. Please see the details below for more information.
                                @elseif($revisionRequest->status === 'in_progress')
                                    is <strong>in progress</strong> and our team is actively working on it.
                                @elseif($revisionRequest->status === 'completed')
                                    is <strong>completed</strong> and is ready for your review.
                                @else
                                    has been <strong>updated</strong> with new information.
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
                    
                    <!-- Revision Update Details Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Revision Status Update
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
                                                Current Status
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                @if($revisionRequest->status === 'approved')
                                                    Approved
                                                @elseif($revisionRequest->status === 'rejected')
                                                    Declined
                                                @elseif($revisionRequest->status === 'in_progress')
                                                    In Progress
                                                @elseif($revisionRequest->status === 'completed')
                                                    Completed
                                                @else
                                                    Under Review
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Reviewed By
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $reviewedBy->name }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Update Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->updated_at->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                        
                                        @if($revisionRequest->estimated_completion)
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                {{ $revisionRequest->status === 'completed' ? 'Completed On' : 'Est. Completion' }}
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $revisionRequest->estimated_completion->format('M d, Y') }}
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            
                            @if($revisionRequest->review_notes)
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #3B82F6; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;">
                                    <strong>
                                        @if($revisionRequest->status === 'approved')
                                            Approval Notes:
                                        @elseif($revisionRequest->status === 'rejected')
                                            Decline Reason:
                                        @elseif($revisionRequest->status === 'completed')
                                            Completion Notes:
                                        @else
                                            Review Notes:
                                        @endif
                                    </strong><br>
                                    {{ $revisionRequest->review_notes }}
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
                            @if($revisionRequest->status === 'completed')
                                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                    Review Completed Work
                                </a>
                            @elseif($revisionRequest->status === 'rejected')
                                <a href="{{ route('client.contact') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                    Discuss Alternatives
                                </a>
                            @else
                                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em;">
                                    Track Progress
                                </a>
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
                                @if($revisionRequest->status === 'completed')
                                    What's Next
                                @elseif($revisionRequest->status === 'rejected')
                                    Alternative Options
                                @else
                                    Keep an Eye On
                                @endif
                            </h3>
                            
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 24px;">
                                @if($revisionRequest->status === 'completed')
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Review Changes</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Carefully review all completed revisions and changes made to your project
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Provide Feedback</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Share your feedback on the completed revisions - we value your input
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif($revisionRequest->status === 'rejected')
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Schedule a Discussion</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Let's talk about alternative approaches that can achieve your goals
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Explore Alternatives</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Consider alternative solutions that fit within project constraints
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">1</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Progress Updates</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Monitor progress through your dashboard or wait for our regular updates
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background-color: #EEF2FF; border-radius: 50%; margin-right: 16px; flex-shrink: 0;">
                                            <span style="font-size: 14px; font-weight: 600; color: #3B82F6;">2</span>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Stay in Touch</div>
                                            <div style="font-size: 13px; color: #64748B; line-height: 1.5;">
                                                Feel free to reach out if you have any questions or concerns
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
                                @if($revisionRequest->status === 'completed')
                                    Proud of our work together,
                                @elseif($revisionRequest->status === 'rejected')
                                    Ready to find solutions,
                                @else
                                    Keeping you informed,
                                @endif
                                <br>
                                <span style="color: #3B82F6;">{{ config('app.name') }} Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                    Revision status update for "{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}". 
                                    <br>
                                    @if($revisionRequest->status === 'completed')
                                        Thank you for trusting us with your project improvements!
                                    @elseif($revisionRequest->status === 'rejected')
                                        We appreciate your understanding and look forward to finding the right solution.
                                    @else
                                        We're committed to keeping you updated throughout the revision process.
                                    @endif
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