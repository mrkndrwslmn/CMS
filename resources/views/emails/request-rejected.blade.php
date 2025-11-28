@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #DC2626; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Project Request Status Update</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Thank you for submitting your project request. After careful review, we regret to inform you that we are unable to proceed with your request at this time.</p>
        
        <!-- Request Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Request Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; width: 50%;">
                            <strong style="color: #1F2937;">Project Name:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right;">
                            {{ $serviceRequest->project_name ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-top: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Service Type:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right; border-top: 1px solid #E5E7EB;">
                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->service_type ?? 'N/A')) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-top: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Requested Date:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right; border-top: 1px solid #E5E7EB;">
                            {{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('F d, Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Reason for Decision Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Reason for Decision</p>
            
            <div style="background: #FEF2F2; border-left: 3px solid #DC2626; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #991B1B; line-height: 1.7;">{{ $rejectionReason }}</p>
            </div>
        </div>
        
        <!-- What's Next Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB; margin-top: 32px;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What's Next?</p>
            
            <ul style="margin: 0; padding-left: 20px; color: #6B7280; font-size: 14px; line-height: 1.8;">
                <li style="margin-bottom: 8px;">If you have questions about this decision, please don't hesitate to contact us</li>
                <li style="margin-bottom: 8px;">You're welcome to submit a revised request addressing the concerns mentioned above</li>
                <li style="margin-bottom: 0;">We may be able to provide alternative solutions that better fit your needs</li>
            </ul>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ config('app.url') }}/client/requests" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View All Requests</a>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Need Help?</p>
            <p style="margin: 0 0 12px 0; font-size: 14px; color: #6B7280; line-height: 1.7;">If you have any questions or would like to discuss this further, our team is here to help.</p>
            <p style="margin: 0; font-size: 14px; color: #6B7280;">
                <strong style="color: #1F2937;">Contact Support:</strong><br>
                <a href="mailto:{{ config('mail.support_email', 'support@treisadiutor.com') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500;">{{ config('mail.support_email', 'support@treisadiutor.com') }}</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0 0 8px 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated message. Please do not reply directly to this email.</p>
    </div>
</div>
@endsection
