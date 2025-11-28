@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Revision Approved</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">Your revision request for <strong style="color: #1F2937;">"{{ $revision->project->project_name }}"</strong> has been approved.</p>
        
        <!-- Revision Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Revision Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $revision->project->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Revision Type</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revision->revision_type ?? 'Standard Revision' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; @if($revision->estimated_completion) border-bottom: 1px solid #E5E7EB; @endif">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Approved Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $revision->approved_at->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @if($revision->estimated_completion)
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Expected Completion</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ \Carbon\Carbon::parse($revision->estimated_completion)->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($revision->description)
            <!-- Description -->
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7;">{{ $revision->description }}</p>
            </div>
            @endif
        </div>
        
        <!-- What Happens Next Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What Happens Next</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Revision Implementation</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team will now begin implementing the approved changes</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Progress Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive regular updates on implementation progress through your dashboard</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Completion & Delivery</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">We'll notify you when your revisions are ready for review</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 32px 0; padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <a href="{{ route('client.dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Track Progress in Dashboard</a>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Questions or Concerns?</p>
            <p style="margin: 0; font-size: 14px; color: #6B7280; line-height: 1.7;">Our team is here to help. You can communicate directly with your assigned team through your project dashboard, or reply to this email with any questions.</p>
        </div>
        
        <!-- Footer Note -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Best regards,</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #3B82F6; font-weight: 500;">{{ config('app.name') }} Team</p>
            <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.6;">Revision approved notification for {{ $revision->project->client->fullName ?? $revision->project->client->full_name ?? 'Client' }}. Thank you for your continued trust in our services.</p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
