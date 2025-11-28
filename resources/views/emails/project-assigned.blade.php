@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">New Project Assignment</h1>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        <p style="margin: 0 0 30px 0; font-size: 15px; color: #4B5563; line-height: 1.7;">You've been assigned to work on <strong style="color: #1F2937;">"{{ $project->title ?? $project->serviceRequest->project_name }}"</strong>.</p>
        
        <!-- Assignment Details Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Assignment Details</p>
            
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="padding: 0 0 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Title</p>
                            <p style="margin: 0; font-size: 15px; color: #1F2937; font-weight: 600;">{{ $project->title ?? $project->serviceRequest->project_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Client</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $project->client->name ?? $project->serviceRequest->client->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Your Role</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">Team Member</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Assignment Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $assignment ? $assignment->created_at->format('M d, Y') : now()->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @if($project->started_at)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Start Date</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $project->started_at->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                    @if($project->deadline)
                    <tr>
                        <td colspan="2" style="padding: 16px 0; border-bottom: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Target Completion</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $project->deadline->format('M d, Y') }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 16px 0 0 0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Project Manager</p>
                            <p style="margin: 0; font-size: 14px; color: #1F2937; font-weight: 500;">{{ $assignedBy->name }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($project->description || $project->serviceRequest->project_description)
            <!-- Project Description -->
            <div style="margin-top: 16px; background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $project->description ?? $project->serviceRequest->project_description }}</p>
            </div>
            @endif
            
            @if($assignment && $assignment->notes)
            <!-- Assignment Notes -->
            <div style="margin-top: 16px; background: #F0FDF4; border-left: 3px solid #16A34A; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #166534;">Assignment Notes</p>
                <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; white-space: pre-line;">{{ $assignment->notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('adiutor.projects.show', $project->id) }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Access Project Workspace</a>
        </div>
        
        <!-- Getting Started Section -->
        <div style="padding: 32px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 20px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Getting Started</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Review Project Materials</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Familiarize yourself with requirements, documentation, and expectations</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Meet Your Team</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Connect with team members and establish communication channels</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Plan Your Approach</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Outline your initial approach and identify any questions needed</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #EFF6FF; color: #3B82F6; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border: 1px solid #BFDBFE; border-radius: 50%;">4</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Begin Contribution</p>
                        <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Start working on your assigned tasks and provide updates</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Questions? Contact our projects team at<br>
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
