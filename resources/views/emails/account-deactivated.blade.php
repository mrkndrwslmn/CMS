@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #6B7280; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Account Deactivated</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Hello {{ $user->fullName }},</p>
        
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">We're writing to inform you that your account with Treis Adiutor has been deactivated by an administrator.</p>
        
        <!-- Account Details Section -->
        <div style="background: #FEF3C7; border: 1px solid #FDE68A; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #92400E;">Account Details</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #78350F;">
                        <strong>Email:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #92400E; text-align: right;">
                        {{ $user->email }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #78350F;">
                        <strong>Role:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #92400E; text-align: right;">
                        {{ ucfirst($user->role) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #78350F;">
                        <strong>Status:</strong>
                    </td>
                    <td style="padding: 4px 0; font-size: 14px; color: #92400E; text-align: right; font-weight: 600;">
                        Inactive
                    </td>
                </tr>
            </table>
        </div>
        
        <p style="margin: 0 0 24px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">If you believe this was done in error or need to reactivate your account, please contact our support team immediately.</p>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="mailto:{{ $supportEmail }}" style="display: inline-block; background: #DC2626; color: white; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Contact Support</a>
        </div>
        
        <p style="margin: 0; font-size: 14px; color: #6B7280; line-height: 1.7;">
            If you have any questions or concerns, please don't hesitate to reach out to our support team at 
            <a href="mailto:{{ $supportEmail }}" style="color: #DC2626; text-decoration: none; font-weight: 500;">{{ $supportEmail }}</a>.
        </p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
