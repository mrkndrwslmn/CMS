@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Meeting Approved</h1>
        <p style="margin: 0; font-size: 14px; color: #D1FAE5; font-weight: 500;">Successfully Scheduled</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Great news! Your meeting request for <strong style="color: #1F2937;">"{{ $meeting->title }}"</strong> has been approved and scheduled.</p>
        
        <!-- Scheduled Date/Time Box -->
        <div style="background: #ECFDF5; border-left: 3px solid #10B981; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #047857; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Scheduled For</p>
            <p style="margin: 0; font-size: 24px; color: #065F46; font-weight: 700; line-height: 1.2;">{{ \Carbon\Carbon::parse($meeting->scheduled_date)->format('F d, Y') }}</p>
            <p style="margin: 8px 0 0 0; font-size: 20px; color: #047857; font-weight: 600;">{{ \Carbon\Carbon::parse($meeting->scheduled_time, 'H:i')->format('g:i A') }}</p>
        </div>
        
        @if($meeting->zoom_join_url)
        <!-- Zoom Meeting Link Section -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #1E40AF;">Zoom Meeting Link</p>
            <a href="{{ $meeting->zoom_join_url }}" style="display: inline-block; background: #2563EB; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 0 16px 0;">Join Meeting</a>
            <p style="margin: 0; font-size: 13px; color: #1E40AF;">
                <strong>Meeting ID:</strong> {{ $meeting->zoom_meeting_id }}
            </p>
            @if($meeting->zoom_password)
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #1E40AF;">
                <strong>Password:</strong> {{ $meeting->zoom_password }}
            </p>
            @endif
        </div>
        @endif
        
        <!-- Meeting Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Meeting Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 0; font-size: 14px; color: #6B7280;">
                            <strong style="color: #1F2937;">Project:</strong>
                        </td>
                        <td style="padding: 0; font-size: 14px; color: #1F2937; text-align: right; font-weight: 500;">
                            {{ $meeting->project->title }}
                        </td>
                    </tr>
                </table>
                
                @if($meeting->description)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #E5E7EB;">
                    <p style="margin: 0; font-size: 14px; color: #6B7280; line-height: 1.7;">{{ $meeting->description }}</p>
                </div>
                @endif
            </div>
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
