@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #DC2626; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Meeting Request Declined</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">We regret to inform you that your meeting request for <strong style="color: #1F2937;">"{{ $meeting->title }}"</strong> could not be approved at this time.</p>
        
        <!-- Meeting Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Meeting Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-bottom: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Project:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right; border-bottom: 1px solid #E5E7EB; font-weight: 500;">
                            {{ $meeting->project->title }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0 0 0; font-size: 14px; color: #6B7280;">
                            <strong style="color: #1F2937;">Requested Date:</strong>
                        </td>
                        <td style="padding: 8px 0 0 0; font-size: 14px; color: #1F2937; text-align: right; font-weight: 500;">
                            {{ \Carbon\Carbon::parse($meeting->requested_date)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($meeting->requested_time, 'H:i')->format('g:i A') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($meeting->admin_notes)
        <!-- Reason Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Reason</p>
            <div style="background: #FEF2F2; border-left: 3px solid #DC2626; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #7F1D1D; line-height: 1.7;">{{ $meeting->admin_notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Next Steps Section -->
        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">What's Next?</p>
            <p style="margin: 0; font-size: 14px; color: #6B7280; line-height: 1.7;">You can submit a new meeting request with alternative dates, or contact support for assistance.</p>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0 0 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ config('app.url') }}/client/projects/{{ $meeting->project_id }}#meetings" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Project</a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0 0 8px 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated message. Please do not reply directly to this email.</p>
    </div>
</div>
@endsection
