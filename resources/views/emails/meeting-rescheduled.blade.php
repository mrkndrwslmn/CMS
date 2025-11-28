@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #F59E0B; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Meeting Rescheduled</h1>
        <p style="margin: 0; font-size: 14px; color: #FEF3C7; font-weight: 500;">New Time Proposed</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">The admin has proposed a new time for your meeting <strong style="color: #1F2937;">"{{ $meeting->title }}"</strong>. Please review and approve or decline.</p>
        
        <!-- Proposed New Time Box -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #92400E; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Proposed New Time</p>
            <p style="margin: 0; font-size: 24px; color: #78350F; font-weight: 700; line-height: 1.2;">{{ \Carbon\Carbon::parse($meeting->rescheduled_date)->format('F d, Y') }}</p>
            <p style="margin: 8px 0 0 0; font-size: 20px; color: #92400E; font-weight: 600;">{{ \Carbon\Carbon::parse($meeting->rescheduled_time, 'H:i')->format('g:i A') }}</p>
        </div>
        
        @if($meeting->admin_notes)
        <!-- Admin Notes Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Admin Notes</p>
            <div style="background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7;">{{ $meeting->admin_notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div style="text-align: center; margin: 32px 0 0 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ config('app.url') }}/client/meetings/{{ $meeting->id }}/approve-reschedule" style="display: inline-block; background: #10B981; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Approve New Time</a>
            <a href="{{ config('app.url') }}/client/meetings/{{ $meeting->id }}/reject-reschedule" style="display: inline-block; background: #6B7280; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Decline</a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0 0 8px 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated message. Please do not reply directly to this email.</p>
    </div>
</div>
@endsection
