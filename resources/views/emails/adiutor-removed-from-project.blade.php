@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #6B7280; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Project Assignment Update</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">We're writing to inform you that you have been removed from the following project.</p>
        
        <!-- Project Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Project Details</p>
            
            <div style="background: #FEF3C7; border: 1px solid #FDE68A; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 0 0 12px 0;">
                            <p style="margin: 0 0 4px 0; font-size: 12px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Title</p>
                            <p style="margin: 0; font-size: 15px; color: #78350F; font-weight: 600;">{{ $projectTitle }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 12px 0;">
                            <p style="margin: 0 0 4px 0; font-size: 12px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Client</p>
                            <p style="margin: 0; font-size: 14px; color: #78350F; font-weight: 500;">{{ $clientName }}</p>
                        </td>
                    </tr>
                    @if($reason)
                    <tr>
                        <td style="padding: 0;">
                            <p style="margin: 0 0 4px 0; font-size: 12px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Reason</p>
                            <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.7;">{{ $reason }}</p>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <p style="margin: 0 0 24px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">This decision was made by the project administration. Any work completed up to this point will be properly compensated according to the agreed terms.</p>
        
        <!-- Next Steps Section -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1E40AF;">Next Steps</p>
            <div style="font-size: 14px; color: #1E3A8A; line-height: 1.7;">
                <p style="margin: 0 0 8px 0;">Please complete any immediate deliverables you may have in progress</p>
                <p style="margin: 0 0 8px 0;">Submit final timesheets or expense reports if applicable</p>
                <p style="margin: 0;">Contact support if you have any questions about compensation or handover</p>
            </div>
        </div>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ $dashboardUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Go to Dashboard</a>
            <a href="mailto:{{ $supportEmail }}" style="display: inline-block; background: #6B7280; color: white; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">Contact Support</a>
        </div>
        
        <p style="margin: 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Thank you for your contributions to this project. We appreciate your professionalism and look forward to working with you on future opportunities.</p>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
