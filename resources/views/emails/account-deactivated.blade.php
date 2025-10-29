@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Account Deactivated</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello {{ $user->fullName }},</p>
        
        <p>We're writing to inform you that your account with Treis Adiutor has been deactivated by an administrator.</p>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Account Details:</strong><br>
            Email: {{ $user->email }}<br>
            Role: {{ ucfirst($user->role) }}<br>
            Status: Inactive
        </div>
        
        <p>If you believe this was done in error or need to reactivate your account, please contact our support team immediately.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="mailto:{{ $supportEmail }}" style="background: #dc3545; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Contact Support</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">
            If you have any questions or concerns, please don't hesitate to reach out to our support team at 
            <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.
        </p>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection