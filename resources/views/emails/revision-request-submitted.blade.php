@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Revision Request Submitted</h1>
        <p style="margin: 0; font-size: 14px; color: #DBEAFE; font-weight: 500;">Request Received</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">A revision request has been submitted for your project <strong style="color: #1F2937;">"{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}"</strong> and has been received by our team for review.</p>
        
        <!-- Revision Request Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Revision Request Details</p>
            
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
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Request Type</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 600;">
                                @if($revisionRequest->type === 'minor')
                                    Minor Revision
                                @elseif($revisionRequest->type === 'major')
                                    Major Revision
                                @else
                                    General Revision
                                @endif
                            </p>
                        </td>
                    </tr>
                    @if($revisionRequest->priority)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Priority</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 600;">
                                @if($revisionRequest->priority === 'high')
                                    High Priority
                                @elseif($revisionRequest->priority === 'medium')
                                    Medium Priority
                                @else
                                    Low Priority
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Submitted By</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revisionRequest->requestedBy->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Request Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revisionRequest->created_at->format('M d, Y H:i') }}</p>
                        </td>
                    </tr>
                    @if($revisionRequest->deadline)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Requested Deadline</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revisionRequest->deadline->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">Under Review</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($revisionRequest->description)
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; color: #1E40AF; font-weight: 600;">Description</p>
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $revisionRequest->description }}</p>
            </div>
            @endif
            
            @if($revisionRequest->specific_requirements)
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; color: #1E40AF; font-weight: 600;">Specific Requirements</p>
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $revisionRequest->specific_requirements }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('client.revisions.show', $revisionRequest->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Track Revision Status</a>
        </div>
        
        <!-- What Happens Next Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What Happens Next</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Initial Review</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team will review your revision request and assess the scope and requirements</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Timeline Estimation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive an estimated timeline and any additional information or clarifications needed</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Implementation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Once approved, our team will begin implementing the requested revisions</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">4</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Completion & Review</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll be notified when revisions are complete and ready for your final review</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Footer Note -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Committed to your satisfaction,</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #3B82F6; font-weight: 500;">{{ config('app.name') }} Team</p>
            <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.6;">Revision request confirmation for "{{ $revisionRequest->project->title ?? $revisionRequest->project->serviceRequest->project_name }}". We're dedicated to ensuring your project meets your exact expectations.</p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
