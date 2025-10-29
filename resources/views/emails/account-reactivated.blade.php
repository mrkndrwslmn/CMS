@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Welcome Back!</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello {{ $user->fullName }},</p>
        
        <p>Great news! Your account with Treis Adiutor has been reactivated and you can now access all features again.</p>
        
        <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Account Details:</strong><br>
            Email: {{ $user->email }}<br>
            Role: {{ ucfirst($user->role) }}<br>
            Status: Active ✅
        </div>
        
        <p>You can now log in to your account and continue using our platform.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" style="background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">Login Now</a>
            <a href="{{ $dashboardUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Go to Dashboard</a>
        </div>
        
        <p>If you have any questions or need assistance getting started again, our support team is here to help.</p>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection