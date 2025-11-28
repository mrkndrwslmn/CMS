@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Welcome to TREIS ADIUTOR</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <h2 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 600; color: #1F2937; letter-spacing: -0.5px;">Welcome, {{ $fullName }}</h2>
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Your account has been successfully created. You can now track your service request and access our platform using the credentials below.</p>
        
        <!-- Login Credentials Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Login Credentials</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</p>
                            <p style="margin: 0; font-family: 'Courier New', monospace; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $email }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Temporary Password</p>
                            <p style="margin: 0; font-family: 'Courier New', monospace; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $password }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Security Notice -->
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 13px; color: #1E40AF; line-height: 1.7;">
                    <strong style="font-weight: 600;">Security Notice:</strong> Please change your password after your first login.
                </p>
            </div>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ url('/login') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Login to Your Account</a>
        </div>
        
        <!-- What You Can Do Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What You Can Do</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #BFDBFE; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Track Service Request</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Monitor progress and receive updates</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #BFDBFE; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Upload Documents</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Share files and resources securely</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #BFDBFE; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Communicate with Adiutor</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Direct messaging and collaboration</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 700; border: 1px solid #BFDBFE; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">View Reports</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Access detailed insights and analytics</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Footer Note -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                This email was sent to {{ $email }}<br>
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
