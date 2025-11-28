@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Welcome to Treis Adiutor</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #1F2937;">Hello <strong>{{ $user->fullName }}</strong>,</p>
        
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Welcome to Treis Adiutor! We're excited to have you join our platform as a <strong>{{ $user->role }}</strong>.</p>
        
        <!-- Account Details Box -->
        <div style="background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 20px 24px; margin: 30px 0; border-radius: 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Your Account Details</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Email:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Role:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ ucfirst($user->role) }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 14px; color: #6B7280;">Status:</td>
                    <td style="padding: 6px 0; font-size: 14px; color: #1F2937; font-weight: 500;">Active</td>
                </tr>
            </table>
        </div>
        
        <p style="margin: 30px 0 16px 0; font-size: 15px; color: #1F2937; font-weight: 600;">Here's what you can do next:</p>
        
        <!-- Features List -->
        <div style="background: #F9FAFB; padding: 24px; margin: 0 0 30px 0; border: 1px solid #E5E7EB; border-radius: 0;">
            <ul style="margin: 0; padding: 0 0 0 20px; list-style: none;">
                @if($user->role === 'client')
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Submit service requests for your projects
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Track project progress and communicate with adiutors
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Access project documents and deliverables
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Provide feedback and reviews
                </li>
                @elseif($user->role === 'adiutor')
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    View and accept project assignments
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Collaborate with clients and manage tasks
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Upload deliverables and track progress
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563; position: relative; padding-left: 24px;">
                    <span style="position: absolute; left: 0; color: #3B82F6; font-weight: bold;">•</span>
                    Build your professional portfolio
                </li>
                @endif
            </ul>
        </div>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 40px 0 30px 0;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Login Now</a>
            <a href="{{ $dashboardUrl }}" style="display: inline-block; background: white; color: #3B82F6; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border: 2px solid #3B82F6; border-radius: 0; margin: 0 6px 12px 6px;">Go to Dashboard</a>
        </div>
        
        <p style="margin: 0; padding-top: 20px; border-top: 1px solid #E5E7EB; font-size: 14px; color: #6B7280; line-height: 1.7;">If you have any questions or need assistance, our support team is here to help you at <a href="mailto:{{ $supportEmail }}" style="color: #3B82F6; text-decoration: none;">{{ $supportEmail }}</a>.</p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
