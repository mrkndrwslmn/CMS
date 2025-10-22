<x-mail::message>
# Project Request Approved

Dear {{ $serviceRequest->client->fullName ?? $serviceRequest->client->full_name ?? 'Valued Client' }},

Great news! Your project request "{{ $serviceRequest->project_name }}" has been approved.

**Project Details:**
- **Project Name:** {{ $serviceRequest->project_name }}
- **Service Type:** {{ $serviceRequest->service_type }}
- **Approved Budget:** ${{ number_format($serviceRequest->approved_budget, 2) }}
- **Payment Due Date:** {{ \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') }}

## Next Steps

To proceed with your project, please complete the payment in your request details.

**Amount:** ${{ number_format($serviceRequest->approved_budget, 2) }}
**Due Date:** {{ \Carbon\Carbon::parse($serviceRequest->payment_due_date)->format('M d, Y') }}

@if($serviceRequest->payment_instructions)
**Payment Instructions:**
{{ $serviceRequest->payment_instructions }}
@endif

<x-mail::button :url="url('/client/requests')">
View Your Requests
</x-mail::button>

Once your payment is confirmed, we will begin working on your project immediately.

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
