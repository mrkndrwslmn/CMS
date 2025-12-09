@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #8B5CF6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Welcome to Treis Adiutor</h1>
        <p style="margin: 10px 0 0 0; font-size: 14px; color: rgba(255,255,255,0.9);">Your adiutor account has been created</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #1F2937;">Hello <strong>{{ $user->fullName }}</strong>,</p>
        
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">
            Welcome to the Treis Adiutor team! An adiutor account has been created for you. You can now access our platform to receive project assignments, track your work hours, manage your earnings, and collaborate with clients.
        </p>
        
        <!-- Important Notice -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; margin: 24px 0; border-radius: 0;">
            <p style="margin: 0; font-size: 14px; color: #92400E;">
                <strong>Important:</strong> For your security, please change your password immediately after logging in for the first time.
            </p>
        </div>
        
        <!-- Account Credentials Box -->
        <div style="background: #F5F3FF; border: 2px solid #8B5CF6; padding: 24px; margin: 30px 0; border-radius: 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #5B21B6; text-transform: uppercase; letter-spacing: 0.5px;">Your Login Credentials</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0; font-size: 14px; color: #6B7280; width: 120px;">Email:</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #1F2937; font-weight: 600;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-size: 14px; color: #6B7280;">Temporary Password:</td>
                    <td style="padding: 10px 0;">
                        <code style="background: #1F2937; color: #F9FAFB; padding: 6px 12px; font-size: 14px; font-family: monospace; letter-spacing: 1px; border-radius: 4px;">{{ $temporaryPassword }}</code>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Security Warning -->
        <div style="background: #FEE2E2; border-left: 3px solid #EF4444; padding: 16px 20px; margin: 24px 0; border-radius: 0;">
            <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #991B1B;">🔒 Security Notice</p>
            <p style="margin: 0; font-size: 13px; color: #991B1B; line-height: 1.6;">
                Never share your password with anyone. Treis Adiutor staff will never ask for your password. If you did not expect this email, please contact our support team immediately.
            </p>
        </div>
        
        <p style="margin: 30px 0 16px 0; font-size: 15px; color: #1F2937; font-weight: 600;">Getting Started:</p>
        
        <!-- Steps List -->
        <div style="background: #F9FAFB; padding: 24px; margin: 0 0 30px 0; border: 1px solid #E5E7EB; border-radius: 0;">
            <ol style="margin: 0; padding: 0 0 0 20px;">
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563;">
                    Click the "Login Now" button below
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563;">
                    Enter your email and the temporary password provided above
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563;">
                    <strong>Change your password immediately</strong> in your account settings
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563;">
                    Complete your adiutor profile with your skills and bio
                </li>
                <li style="padding: 8px 0; font-size: 14px; color: #4B5563;">
                    Start receiving project assignments
                </li>
            </ol>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 40px 0 30px 0;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background: #8B5CF6; color: white; padding: 16px 48px; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 0;">Login Now</a>
        </div>
        
        <!-- What You Can Do Section -->
        <div style="margin: 30px 0; padding: 24px; background: #F5F3FF; border: 1px solid #DDD6FE; border-radius: 0;">
            <p style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #5B21B6;">As an adiutor, you can:</p>
            <ul style="margin: 0; padding: 0 0 0 20px; list-style: none;">
                <li style="padding: 6px 0; font-size: 14px; color: #5B21B6; position: relative; padding-left: 20px;">
                    <span style="position: absolute; left: 0;">✓</span>
                    Receive and manage project assignments
                </li>
                <li style="padding: 6px 0; font-size: 14px; color: #5B21B6; position: relative; padding-left: 20px;">
                    <span style="position: absolute; left: 0;">✓</span>
                    Track your work hours and earnings
                </li>
                <li style="padding: 6px 0; font-size: 14px; color: #5B21B6; position: relative; padding-left: 20px;">
                    <span style="position: absolute; left: 0;">✓</span>
                    Communicate directly with clients
                </li>
                <li style="padding: 6px 0; font-size: 14px; color: #5B21B6; position: relative; padding-left: 20px;">
                    <span style="position: absolute; left: 0;">✓</span>
                    Upload deliverables and manage documents
                </li>
                <li style="padding: 6px 0; font-size: 14px; color: #5B21B6; position: relative; padding-left: 20px;">
                    <span style="position: absolute; left: 0;">✓</span>
                    Request withdrawals for your earnings
                </li>
            </ul>
        </div>
        
        <p style="margin: 0; padding-top: 20px; border-top: 1px solid #E5E7EB; font-size: 14px; color: #6B7280; line-height: 1.7;">
            If you have any questions or need assistance, don't hesitate to reach out to us at <a href="mailto:{{ $supportEmail }}" style="color: #8B5CF6;">{{ $supportEmail }}</a>.
        </p>
        
        <p style="margin: 20px 0 0 0; font-size: 14px; color: #6B7280;">
            We're excited to have you on the team!<br>
            <strong style="color: #1F2937;">The Treis Adiutor Team</strong>
        </p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; padding: 24px 30px; border-top: 1px solid #E5E7EB; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">
            This is an automated message from Treis Adiutor. Please do not reply directly to this email.
        </p>
    </div>
</div>
@endsection
