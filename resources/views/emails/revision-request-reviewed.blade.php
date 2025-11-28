@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">
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
        <p style="margin: 0; font-size: 14px; color: #DBEAFE; font-weight: 500;">Status Update</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">
            Your revision request for <strong style="color: #1F2937;">"{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}"</strong> 
            @if($revisionRequest->status === 'approved')
                has been <strong style="color: #1F2937;">approved</strong> and work will begin shortly.
            @elseif($revisionRequest->status === 'rejected')
                has been <strong style="color: #1F2937;">declined</strong>. Please see the details below for more information.
            @elseif($revisionRequest->status === 'in_progress')
                is <strong style="color: #1F2937;">in progress</strong> and our team is actively working on it.
            @elseif($revisionRequest->status === 'completed')
                is <strong style="color: #1F2937;">completed</strong> and is ready for your review.
            @else
                has been <strong style="color: #1F2937;">updated</strong> with new information.
            @endif
        </p>
        
        <!-- Revision Status Update Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Revision Status Update</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Current Status</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 600;">
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
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Reviewed By</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $reviewedBy->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; @if($revisionRequest->estimated_completion) border-bottom: 1px solid #E5E7EB; @endif">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Update Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revisionRequest->updated_at->format('M d, Y H:i') }}</p>
                        </td>
                    </tr>
                    @if($revisionRequest->estimated_completion)
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">{{ $revisionRequest->status === 'completed' ? 'Completed On' : 'Est. Completion' }}</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revisionRequest->estimated_completion->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($revisionRequest->review_notes)
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; color: #1E40AF; font-weight: 600;">
                    @if($revisionRequest->status === 'approved')
                        Approval Notes
                    @elseif($revisionRequest->status === 'rejected')
                        Decline Reason
                    @elseif($revisionRequest->status === 'completed')
                        Completion Notes
                    @else
                        Review Notes
                    @endif
                </p>
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7;">{{ $revisionRequest->review_notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            @if($revisionRequest->status === 'completed')
                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Review Completed Work</a>
            @elseif($revisionRequest->status === 'rejected')
                <a href="{{ route('client.contact') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Discuss Alternatives</a>
            @else
                <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Track Progress</a>
            @endif
        </div>
        
        <!-- Next Steps Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">
                @if($revisionRequest->status === 'completed')
                    What's Next
                @elseif($revisionRequest->status === 'rejected')
                    Alternative Options
                @else
                    Keep an Eye On
                @endif
            </p>
            
            <table style="width: 100%; border-collapse: collapse;">
                @if($revisionRequest->status === 'completed')
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Review Changes</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Carefully review all completed revisions and changes made to your project</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Provide Feedback</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Share your feedback on the completed revisions - we value your input</p>
                    </td>
                </tr>
                @elseif($revisionRequest->status === 'rejected')
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Schedule a Discussion</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Let's talk about alternative approaches that can achieve your goals</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Explore Alternatives</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Consider alternative solutions that fit within project constraints</p>
                    </td>
                </tr>
                @else
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Progress Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Monitor progress through your dashboard or wait for our regular updates</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Stay in Touch</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Feel free to reach out if you have any questions or concerns</p>
                    </td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Footer Note -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">
                @if($revisionRequest->status === 'completed')
                    Proud of our work together,
                @elseif($revisionRequest->status === 'rejected')
                    Ready to find solutions,
                @else
                    Keeping you informed,
                @endif
            </p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #3B82F6; font-weight: 500;">{{ config('app.name') }} Team</p>
            <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.6;">
                Revision status update for "{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}". 
                @if($revisionRequest->status === 'completed')
                    Thank you for trusting us with your project improvements!
                @elseif($revisionRequest->status === 'rejected')
                    We appreciate your understanding and look forward to finding the right solution.
                @else
                    We're committed to keeping you updated throughout the revision process.
                @endif
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
