@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #DC2626; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Project Cancelled</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">We regret to inform you that the following project has been cancelled:</p>
        
        <!-- Project Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Project Details</p>
            
            <div style="background: #FEF2F2; border-left: 3px solid #DC2626; padding: 20px 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; width: 80px;">
                            <strong>Title:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; font-weight: 600;">
                            {{ $project->title }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B;">
                            <strong>Client:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; font-weight: 500;">
                            {{ $project->client->fullName ?? 'Unknown' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B;">
                            <strong>Status:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; font-weight: 600;">
                            Cancelled
                        </td>
                    </tr>
                    @if($reason)
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; vertical-align: top;">
                            <strong>Reason:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #991B1B; font-weight: 500;">
                            {{ $reason }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Description Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Description</p>
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px 24px;">
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7;">{{ $project->description }}</p>
            </div>
        </div>
        
        @if($project->budget)
        <!-- Budget -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #6B7280;">
                <strong style="color: #1F2937;">Budget:</strong> ${{ number_format($project->budget, 2) }}
            </p>
        </div>
        @endif
        
        <p style="margin: 24px 0 0 0; padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; font-size: 14px; color: #6B7280; line-height: 1.7;">We apologize for any inconvenience this may cause. If you have any questions or concerns about this cancellation, please don't hesitate to reach out to our support team.</p>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 32px 0 0 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ $dashboardUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Go to Dashboard</a>
            <a href="mailto:{{ $supportEmail }}" style="display: inline-block; background: #6B7280; color: white; padding: 14px 28px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Contact Support</a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
