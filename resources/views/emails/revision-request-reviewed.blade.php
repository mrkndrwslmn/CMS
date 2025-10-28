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
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#ECFDF5 0%, #D1FAE5 50%, #A7F3D0 100%' : '#FEF2F2 0%, #FECACA 50%, #F87171 100%' }}); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#ECFDF5 0%, #D1FAE5 50%, #A7F3D0 100%' : '#FEF2F2 0%, #FECACA 50%, #F87171 100%' }}); min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 20px 40px rgba({{ $revisionRequest->status === 'approved' ? '16, 185, 129' : '239, 68, 68' }}, 0.08), 0 8px 32px rgba(0, 0, 0, 0.03); border: 1px solid rgba(241, 245, 249, 0.9); overflow: hidden;">
                    
                    <!-- Header with Dynamic Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#10B981, #059669' : $revisionRequest->status === 'rejected' ? '#EF4444, #DC2626' : '#F59E0B, #D97706' }}); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba({{ $revisionRequest->status === 'approved' ? '16, 185, 129' : $revisionRequest->status === 'rejected' ? '239, 68, 68' : '245, 158, 11' }}, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Status Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">
                                @if($revisionRequest->status === 'approved')
                                    ✅
                                @elseif($revisionRequest->status === 'rejected')
                                    ❌
                                @elseif($revisionRequest->status === 'in_progress')
                                    🔄
                                @else
                                    📋
                                @endif
                            </div>
                            
                            <!-- Logo/Brand -->
                            <h1 style="margin: 0 0 8px; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Projects Department</p>
                        </td>
                    </tr>
                    
                    <!-- Status Update Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba({{ $revisionRequest->status === 'approved' ? '16, 185, 129' : $revisionRequest->status === 'rejected' ? '239, 68, 68' : '245, 158, 11' }}, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Revision Request 
                                    @if($revisionRequest->status === 'approved')
                                        Approved!
                                    @elseif($revisionRequest->status === 'rejected')
                                        Declined
                                    @elseif($revisionRequest->status === 'in_progress')
                                        In Progress
                                    @elseif($revisionRequest->status === 'completed')
                                        Completed!
                                    @else
                                        Updated
                                    @endif
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Your revision request for <strong style="color: #1E293B;">"{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}"</strong> has been 
                                    @if($revisionRequest->status === 'approved')
                                        <strong style="color: #059669;">approved</strong> and work will begin shortly.
                                    @elseif($revisionRequest->status === 'rejected')
                                        <strong style="color: #DC2626;">declined</strong>. Please see the details below.
                                    @elseif($revisionRequest->status === 'in_progress')
                                        <strong style="color: #D97706;">started</strong> and our team is actively working on it.
                                    @elseif($revisionRequest->status === 'completed')
                                        <strong style="color: #059669;">completed</strong> and is ready for your review.
                                    @else
                                        <strong style="color: #7C3AED;">updated</strong> with new information.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Revision Update Details Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#ECFDF5 0%, #D1FAE5 100%' : $revisionRequest->status === 'rejected' ? '#FEF2F2 0%, #FECACA 100%' : '#FFFBEB 0%, #FEF3C7 100%' }}); border-radius: 16px; padding: 32px; border: 1px solid {{ $revisionRequest->status === 'approved' ? '#BBF7D0' : $revisionRequest->status === 'rejected' ? '#FECACA' : '#FDE68A' }}; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, {{ $revisionRequest->status === 'approved' ? '#10B981, #059669' : $revisionRequest->status === 'rejected' ? '#EF4444, #DC2626' : '#F59E0B, #D97706' }});"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#10B981, #059669' : $revisionRequest->status === 'rejected' ? '#EF4444, #DC2626' : '#F59E0B, #D97706' }}); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">📝</span>
                                    </span>
                                    Revision Status Update
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 20px;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Project</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 18px; font-weight: 700; color: #1E293B;">{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Current Status</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                @if($revisionRequest->status === 'approved')
                                                    <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">✅ Approved</span>
                                                @elseif($revisionRequest->status === 'rejected')
                                                    <span style="background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #B91C1C; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #FECACA;">❌ Declined</span>
                                                @elseif($revisionRequest->status === 'in_progress')
                                                    <span style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #FDE68A;">🔄 In Progress</span>
                                                @elseif($revisionRequest->status === 'completed')
                                                    <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">🎉 Completed</span>
                                                @else
                                                    <span style="background: linear-gradient(135deg, #E0E7FF, #C7D2FE); color: #3730A3; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #C7D2FE;">📋 Under Review</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Reviewed By</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $reviewedBy->name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Update Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $revisionRequest->updated_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($revisionRequest->estimated_completion)
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">{{ $revisionRequest->status === 'completed' ? 'Completed On' : 'Est. Completion' }}</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $revisionRequest->estimated_completion->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($revisionRequest->review_notes)
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0; margin-bottom: 16px;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #1E293B;">
                                        @if($revisionRequest->status === 'approved')
                                            Approval Notes:
                                        @elseif($revisionRequest->status === 'rejected')
                                            Decline Reason:
                                        @elseif($revisionRequest->status === 'completed')
                                            Completion Notes:
                                        @else
                                            Review Notes:
                                        @endif
                                    </h4>
                                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-line;">{{ $revisionRequest->review_notes }}</p>
                                </div>
                                @endif
                                
                                <div style="background: {{ $revisionRequest->status === 'approved' ? '#DCFCE7' : $revisionRequest->status === 'rejected' ? '#FEE2E2' : '#FEF3C7' }}; border: 1px solid {{ $revisionRequest->status === 'approved' ? '#BBF7D0' : $revisionRequest->status === 'rejected' ? '#FECACA' : '#FDE68A' }}; border-radius: 8px; padding: 16px;">
                                    <p style="margin: 0; font-size: 14px; color: {{ $revisionRequest->status === 'approved' ? '#065F46' : $revisionRequest->status === 'rejected' ? '#B91C1C' : '#92400E' }}; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">
                                            @if($revisionRequest->status === 'approved')
                                                🎉
                                            @elseif($revisionRequest->status === 'rejected')
                                                💬
                                            @elseif($revisionRequest->status === 'completed')
                                                ✨
                                            @else
                                                🔔
                                            @endif
                                        </span>
                                        <span>
                                            <strong>
                                                @if($revisionRequest->status === 'approved')
                                                    Great News!
                                                @elseif($revisionRequest->status === 'rejected')
                                                    Let's Discuss!
                                                @elseif($revisionRequest->status === 'completed')
                                                    Ready for Review!
                                                @else
                                                    Stay Updated!
                                                @endif
                                            </strong> 
                                            @if($revisionRequest->status === 'approved')
                                                Your revision request has been approved and our team will begin working on it according to the estimated timeline.
                                            @elseif($revisionRequest->status === 'rejected')
                                                Please review the decline reason above. We're happy to discuss alternative solutions that meet your needs.
                                            @elseif($revisionRequest->status === 'completed')
                                                Your revision is complete and ready for your review. Please check the updated deliverables.
                                            @else
                                                Your revision request status has been updated. Check your dashboard for the latest information.
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            @if($revisionRequest->status === 'completed')
                                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25), 0 4px 8px rgba(16, 185, 129, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                    <span style="display: inline-block; margin-right: 8px;">📋</span>
                                    Review Completed Work
                                </a>
                            @elseif($revisionRequest->status === 'rejected')
                                <a href="{{ route('client.contact') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                    <span style="display: inline-block; margin-right: 8px;">💬</span>
                                    Discuss Alternatives
                                </a>
                            @else
                                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background: linear-gradient(135deg, #F59E0B, #D97706); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.25), 0 4px 8px rgba(245, 158, 11, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                    <span style="display: inline-block; margin-right: 8px;">📊</span>
                                    Track Progress
                                </a>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Next Steps Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0;">
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B; text-align: center;">
                                    @if($revisionRequest->status === 'completed')
                                        What's Next?
                                    @elseif($revisionRequest->status === 'rejected')
                                        Alternative Options
                                    @else
                                        Keep an Eye On
                                    @endif
                                </h3>
                                
                                <div style="display: table; width: 100%;">
                                    @if($revisionRequest->status === 'completed')
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">👀</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Review Changes</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Carefully review all completed revisions and changes made to your project</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Provide Feedback</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Share your feedback on the completed revisions - we value your input!</span>
                                        </div>
                                    </div>
                                    @elseif($revisionRequest->status === 'rejected')
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Schedule a Discussion</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Let's talk about alternative approaches that can achieve your goals</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">🔄</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Explore Alternatives</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Consider alternative solutions that fit within project constraints</span>
                                        </div>
                                    </div>
                                    @else
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📊</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Progress Updates</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Monitor progress through your dashboard or wait for our regular updates</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Stay in Touch</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Feel free to reach out if you have any questions or concerns</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div style="background: {{ $revisionRequest->status === 'completed' ? '#EFF6FF' : $revisionRequest->status === 'rejected' ? '#FEF2F2' : '#FFFBEB' }}; border: 1px solid {{ $revisionRequest->status === 'completed' ? '#BFDBFE' : $revisionRequest->status === 'rejected' ? '#FECACA' : '#FDE68A' }}; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                    <p style="margin: 0; font-size: 14px; color: {{ $revisionRequest->status === 'completed' ? '#1D4ED8' : $revisionRequest->status === 'rejected' ? '#B91C1C' : '#92400E' }}; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">
                                            @if($revisionRequest->status === 'completed')
                                                🌟
                                            @elseif($revisionRequest->status === 'rejected')
                                                🤝
                                            @else
                                                📞
                                            @endif
                                        </span>
                                        <span>
                                            <strong>
                                                @if($revisionRequest->status === 'completed')
                                                    Quality Delivered!
                                                @elseif($revisionRequest->status === 'rejected')
                                                    Let's Collaborate!
                                                @else
                                                    We're Here to Help!
                                                @endif
                                            </strong> 
                                            @if($revisionRequest->status === 'completed')
                                                We're committed to ensuring every revision meets your exact expectations and enhances your project.
                                            @elseif($revisionRequest->status === 'rejected')
                                                Our team is always ready to find creative solutions that work within your project's scope and timeline.
                                            @else
                                                If you have any questions about the revision process or timeline, don't hesitate to reach out to our team.
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>
                                    @if($revisionRequest->status === 'completed')
                                        Proud of our work together,
                                    @elseif($revisionRequest->status === 'rejected')
                                        Ready to find solutions,
                                    @else
                                        Keeping you informed,
                                    @endif
                                </strong><br>
                                <span style="background: linear-gradient(135deg, {{ $revisionRequest->status === 'approved' ? '#10B981, #059669' : $revisionRequest->status === 'rejected' ? '#EF4444, #DC2626' : '#F59E0B, #D97706' }}); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Projects Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    Revision status update for "{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}". 
                                    <br>
                                    @if($revisionRequest->status === 'completed')
                                        Thank you for trusting us with your project improvements!
                                    @elseif($revisionRequest->status === 'rejected')
                                        We appreciate your understanding and look forward to finding the right solution.
                                    @else
                                        We're committed to keeping you updated throughout the revision process.
                                    @endif
                                    <br><br>
                                    <span style="color: #64748B;">Digital Innovation • Intelligence • Excellence</span>
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