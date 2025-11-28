@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Task Completed Successfully</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">The task has been completed. Review the details below for more information.</p>
        
        <!-- Task Summary Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Task Summary</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Task Title</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $task->taskTitle }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $task->project->title ?? $task->project->serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Completed By</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $completedBy->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Completed Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $task->completedAt->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @if($task->deadline)
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                            <p style="margin: 0; font-size: 14px; font-weight: 600;">
                                @if($task->completedAt <= $task->deadline)
                                    <span style="color: #059669;">On Time</span>
                                @else
                                    <span style="color: #DC2626;">Completed Late</span>
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($task->completion_notes)
            <!-- Completion Notes -->
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #1F2937; line-height: 1.6;">
                    <strong style="color: #1E40AF;">Notes:</strong> <span style="color: #4B5563;">{{ $task->completion_notes }}</span>
                </p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('client.dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Project Progress</a>
        </div>
        
        <!-- Next Steps Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Next Steps</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 28px; padding: 0 0 16px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 24px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE;">1</span>
                    </td>
                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Review Deliverables</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Check the completed work and any associated documents</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 28px; padding: 0 0 16px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 24px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE;">2</span>
                    </td>
                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Provide Feedback</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Share your thoughts on the completed task</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 28px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 24px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Continue Project</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Move forward with the next phase of your project</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 4px 0; font-size: 13px; color: #6B7280;">Questions? Contact your project manager or reach out to</p>
            <p style="margin: 0; font-size: 13px;">
                <a href="mailto:support@treisadiutor.com" style="color: #3B82F6; text-decoration: none; font-weight: 500;">support@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
