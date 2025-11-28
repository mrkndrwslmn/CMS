@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #DC2626; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">URGENT PRIORITY TASK</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #1F2937;">Hello,</p>
        
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;"><strong style="color: #1F2937;">Important:</strong> One of your assigned tasks has been marked as <strong style="color: #DC2626;">URGENT PRIORITY</strong> and requires immediate attention.</p>
        
        <!-- Task Details Box -->
        <div style="background: #FEF2F2; border-left: 3px solid #DC2626; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Task Details</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280; width: 90px;">Title:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $task->taskTitle }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Project:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $projectTitle }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Priority:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #DC2626; font-weight: 600;">URGENT</td>
                </tr>
                @if($task->deadline)
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Deadline:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y g:i A') }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Description Box -->
        <div style="background: white; border: 1px solid #E5E7EB; padding: 20px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Description</p>
            <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7;">{{ $task->taskDescription }}</p>
        </div>
        
        <!-- Action Required Box -->
        <div style="background: #FEF3C7; border: 1px solid #FCD34D; border-left: 3px solid #F59E0B; padding: 20px; margin: 0 0 32px 0;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #92400E;">Action Required</p>
            <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.7;">Please review this task immediately and prioritize it in your workflow. If you have any concerns about the timeline or resources needed, contact your project manager as soon as possible.</p>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ $taskUrl }}" style="display: inline-block; background: #DC2626; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">VIEW URGENT TASK</a>
        </div>
        
        <!-- Footer Note -->
        <p style="margin: 0; padding-top: 20px; border-top: 1px solid #E5E7EB; font-size: 13px; color: #6B7280; line-height: 1.7;">This task has been escalated due to business requirements. Please acknowledge receipt of this email and provide an estimated completion time at your earliest convenience.</p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
