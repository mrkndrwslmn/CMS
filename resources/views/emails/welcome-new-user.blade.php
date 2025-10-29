@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Welcome to Treis Adiutor!</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello {{ $user->fullName }},</p>
        
        <p>Welcome to Treis Adiutor! We're excited to have you join our platform as a {{ $user->role }}.</p>
        
        <div style="background: #d1ecf1; border: 1px solid #b8daff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Your Account Details:</strong><br>
            Email: {{ $user->email }}<br>
            Role: {{ ucfirst($user->role) }}<br>
            Status: Active
        </div>
        
        <p>Here's what you can do next:</p>
        
        <ul style="background: white; padding: 20px; border-radius: 5px; border-left: 4px solid #007bff;">
            @if($user->role === 'client')
            <li>Submit service requests for your projects</li>
            <li>Track project progress and communicate with adiutors</li>
            <li>Access project documents and deliverables</li>
            <li>Provide feedback and reviews</li>
            @elseif($user->role === 'adiutor')
            <li>View and accept project assignments</li>
            <li>Collaborate with clients and manage tasks</li>
            <li>Upload deliverables and track progress</li>
            <li>Build your professional portfolio</li>
            @endif
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">Login Now</a>
            <a href="{{ $dashboardUrl }}" style="background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Go to Dashboard</a>
        </div>
        
        <p>If you have any questions or need assistance, our support team is here to help you at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection