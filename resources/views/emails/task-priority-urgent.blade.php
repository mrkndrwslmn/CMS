@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">🚨 URGENT PRIORITY TASK</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello,</p>
        
        <p><strong>Important:</strong> One of your assigned tasks has been marked as <span style="color: #dc3545; font-weight: bold;">URGENT PRIORITY</span> and requires immediate attention.</p>
        
        <div style="background: #f8d7da; border: 2px solid #dc3545; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Task Details:</strong><br>
            <strong>Title:</strong> {{ $task->taskTitle }}<br>
            <strong>Project:</strong> {{ $projectTitle }}<br>
            <strong>Priority:</strong> <span style="color: #dc3545; font-weight: bold;">URGENT</span><br>
            @if($task->deadline)
            <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y g:i A') }}
            @endif
        </div>
        
        <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545; margin: 20px 0;">
            <strong>Description:</strong><br>
            {{ $task->taskDescription }}
        </div>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>⚠️ Action Required:</strong><br>
            Please review this task immediately and prioritize it in your workflow. If you have any concerns about the timeline or resources needed, contact your project manager as soon as possible.
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $taskUrl }}" style="background: #dc3545; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">VIEW URGENT TASK</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">
            This task has been escalated due to business requirements. Please acknowledge receipt of this email and provide an estimated completion time at your earliest convenience.
        </p>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection