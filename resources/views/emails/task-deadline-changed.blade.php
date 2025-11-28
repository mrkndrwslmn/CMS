@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Task Deadline Updated</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #1F2937;">Hello,</p>
        
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">The deadline for one of your assigned tasks has been updated:</p>
        
        <!-- Task Details Box -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Task Details</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F; width: 80px;">Title:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F; font-weight: 500;">{{ $task->taskTitle }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F;">Project:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F; font-weight: 500;">{{ $task->project->title ?? 'Unknown Project' }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F;">Priority:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #78350F; font-weight: 500;">{{ ucfirst($task->priority) }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Deadline Comparison -->
        <div style="background: white; border: 1px solid #E5E7EB; padding: 24px; margin: 0 0 24px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding: 0 12px 0 0; vertical-align: top; border-right: 1px solid #E5E7EB;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Old Deadline</p>
                        <p style="margin: 0; font-size: 16px; color: #DC2626; font-weight: 500;">{{ $oldDeadline ? \Carbon\Carbon::parse($oldDeadline)->format('M d, Y') : 'No deadline' }}</p>
                    </td>
                    <td style="width: 50%; padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">New Deadline</p>
                        <p style="margin: 0; font-size: 16px; color: #059669; font-weight: 500;">{{ $newDeadline ? \Carbon\Carbon::parse($newDeadline)->format('M d, Y') : 'No deadline' }}</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p style="margin: 0 0 32px 0; font-size: 14px; color: #6B7280; line-height: 1.7;">Please update your schedule accordingly and ensure the task is completed by the new deadline.</p>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 0 0;">
            <a href="{{ $taskUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Task</a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
