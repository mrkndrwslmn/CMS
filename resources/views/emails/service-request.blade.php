@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">New Service Request</h1>
        <p style="margin: 0; font-size: 14px; color: #DBEAFE; font-weight: 500;">Request Management</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #1F2937; line-height: 1.7;">
            A new service request has been submitted by <strong>{{ $serviceRequest->user->fullName }}</strong>
            @if($isNewUser)
            <br><span style="display: inline-block; background: #FEF3C7; color: #92400E; padding: 4px 10px; font-size: 12px; font-weight: 600; margin-top: 8px; border: 1px solid #FCD34D;">NEW USER</span>
            @endif
        </p>
        
        <!-- Request Information Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Request Information</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Name</p>
                            <p style="margin: 0; font-size: 16px; color: #1F2937; font-weight: 600;">{{ $serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Submitted By</p>
                            <p style="margin: 0 0 4px 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $serviceRequest->user->fullName }}</p>
                            <p style="margin: 0; font-size: 13px; color: #6B7280;">{{ $serviceRequest->user->email }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Submitted Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $serviceRequest->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ ucfirst($serviceRequest->status) }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($serviceRequest->project_description)
        <!-- Project Description -->
        <div style="margin: 0 0 16px 0; background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Project Description</p>
            <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $serviceRequest->project_description }}</p>
        </div>
        @endif
        
        @if($serviceRequest->budget_range)
        <!-- Budget Range -->
        <div style="margin: 0 0 16px 0; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
            <p style="margin: 0; font-size: 14px; color: #1E40AF;"><strong>Budget Range:</strong> {{ $serviceRequest->budget_range }}</p>
        </div>
        @endif
        
        @if($serviceRequest->preferred_timeline)
        <!-- Preferred Timeline -->
        <div style="margin: 0 0 24px 0; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
            <p style="margin: 0; font-size: 14px; color: #1E40AF;"><strong>Preferred Timeline:</strong> {{ $serviceRequest->preferred_timeline }}</p>
        </div>
        @endif
        
        <!-- Required Actions Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Required Actions</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Review Details</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Examine the request thoroughly and assess requirements</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Evaluate Feasibility</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Assess technical requirements and resource availability</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Take Action</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Approve, request more info, or decline through the admin panel</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ route('admin.requests.show', $serviceRequest->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Full Request</a>
        </div>
        
        <!-- Footer Note -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Request Management Alert</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #3B82F6; font-weight: 500;">{{ config('app.name') }} Admin Team</p>
            <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.6;">Service request notification for "{{ $serviceRequest->project_name }}". This is an automated alert from your management system.</p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
