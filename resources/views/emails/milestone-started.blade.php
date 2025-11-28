@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Milestone Started</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">We've officially started working on <strong style="color: #1F2937;">"{{ $milestone->title }}"</strong> for your project.</p>
        
        <!-- Milestone Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Milestone Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Milestone Title</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $milestone->title }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $milestone->project->title ?? $milestone->project->serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Start Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $milestone->start_date->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @if($milestone->due_date)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Target Completion</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $milestone->due_date->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                    @if($milestone->budget_allocation)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Budget Allocation</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">₱{{ number_format($milestone->budget_allocation, 2) }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">In Progress</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($milestone->description)
            <!-- Description -->
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $milestone->description }}</p>
            </div>
            @endif
            
            @if($milestone->deliverables)
            <!-- Expected Deliverables -->
            <div style="margin-top: 16px; background: #F0FDF4; border-left: 3px solid #10B981; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #065F46;">Expected Deliverables</p>
                <p style="margin: 0; font-size: 14px; color: #047857; line-height: 1.7; white-space: pre-line;">{{ $milestone->deliverables }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('client.milestones.show', $milestone->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Track Milestone Progress</a>
        </div>
        
        <!-- What to Expect Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">What to Expect</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Regular Progress Updates</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll receive periodic updates on milestone progress and key developments</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Communication Touchpoints</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Our team will reach out for any clarifications or feedback needed</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #DBEAFE; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Milestone Completion</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">You'll be notified immediately when the milestone is completed and ready for review</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions? Contact our team at<br>
                <a href="mailto:projects@treisadiutor.com" style="color: #3B82F6; text-decoration: none; font-weight: 500;">projects@treisadiutor.com</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
