@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Welcome Back</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Hello {{ $user->fullName }},</p>
        
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Great news! Your account with Treis Adiutor has been reactivated and you can now access all features again.</p>
        
        <!-- Account Details Section -->
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #065F46;">Account Details</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #047857;">
                        <strong>Email:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #065F46; text-align: right;">
                        {{ $user->email }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #047857;">
                        <strong>Role:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #065F46; text-align: right;">
                        {{ ucfirst($user->role) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #047857;">
                        <strong>Status:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #065F46; text-align: right; font-weight: 600;">
                        Active
                    </td>
                </tr>
            </table>
        </div>
        
        <p style="margin: 0 0 24px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">You can now log in to your account and continue using our platform.</p>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background: #10B981; color: white; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Login Now</a>
            <a href="{{ $dashboardUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Go to Dashboard</a>
        </div>
        
        <p style="margin: 0; font-size: 15px; color: #4B5563; line-height: 1.7;">If you have any questions or need assistance getting started again, our support team is here to help.</p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
