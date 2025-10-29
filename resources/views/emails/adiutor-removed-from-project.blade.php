@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Removed from Project</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello,</p>
        
        <p>We're writing to inform you that you have been removed from the following project:</p>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Project Details:</strong><br>
            <strong>Title:</strong> {{ $projectTitle }}<br>
            <strong>Client:</strong> {{ $clientName }}<br>
            @if($reason)
            <strong>Reason:</strong> {{ $reason }}
            @endif
        </div>
        
        <p>This decision was made by the project administration. Any work completed up to this point will be properly compensated according to the agreed terms.</p>
        
        <div style="background: #d1ecf1; border: 1px solid #b8daff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Next Steps:</strong><br>
            • Please complete any immediate deliverables you may have in progress<br>
            • Submit final timesheets or expense reports if applicable<br>
            • Contact support if you have any questions about compensation or handover
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $dashboardUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">Go to Dashboard</a>
            <a href="mailto:{{ $supportEmail }}" style="background: #6c757d; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Contact Support</a>
        </div>
        
        <p>Thank you for your contributions to this project. We appreciate your professionalism and look forward to working with you on future opportunities.</p>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection