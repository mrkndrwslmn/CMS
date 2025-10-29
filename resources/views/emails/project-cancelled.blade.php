@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Project Cancelled</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello,</p>
        
        <p>We regret to inform you that the following project has been cancelled:</p>
        
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Project Details:</strong><br>
            <strong>Title:</strong> {{ $project->title }}<br>
            <strong>Client:</strong> {{ $project->client->fullName ?? 'Unknown' }}<br>
            <strong>Status:</strong> Cancelled<br>
            @if($reason)
            <strong>Reason:</strong> {{ $reason }}
            @endif
        </div>
        
        <p><strong>Description:</strong></p>
        <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545;">
            {{ $project->description }}
        </div>
        
        @if($project->budget)
        <p><strong>Budget:</strong> ${{ number_format($project->budget, 2) }}</p>
        @endif
        
        <p>We apologize for any inconvenience this may cause. If you have any questions or concerns about this cancellation, please don't hesitate to reach out to our support team.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $dashboardUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">Go to Dashboard</a>
            <a href="mailto:{{ $supportEmail }}" style="background: #6c757d; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Contact Support</a>
        </div>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection