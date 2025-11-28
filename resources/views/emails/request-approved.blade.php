@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Project Request Approved</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Great news! Your project request has been reviewed and approved. The next step is to complete the payment to begin the project.</p>
        
        <!-- Project Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Project Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; width: 140px; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Name</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Service Type</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $serviceRequest->service_type ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Approved Budget</p>
                        </td>
                        <td style="padding: 12px 0; vertical-align: top; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 700;">₱{{ number_format($serviceRequest->approved_budget ?? 0, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0 0 0; vertical-align: top;">
                            <p style="margin: 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Payment Due Date</p>
                        </td>
                        <td style="padding: 12px 0 0 0; vertical-align: top;">
                            <p style="margin: 0; font-size: 14px; color: #DC2626; font-weight: 600;">{{ $serviceRequest->payment_due_date ? \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') : 'N/A' }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Next Steps Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Next Steps</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Process Payment</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Complete the payment to confirm your project</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Payment Confirmation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive confirmation and project details</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Project Kickoff</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team begins working on your project immediately</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0 0 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ url('/dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Review Your Request</a>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center; margin-top: 32px;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions or need help?<br>
                Contact us at <a href="mailto:support@treisadiutor.com" style="color: #3B82F6; text-decoration: none; font-weight: 500;">support@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
