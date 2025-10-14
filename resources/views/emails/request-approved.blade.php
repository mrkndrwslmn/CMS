<x-mail::message>
# Project Request Approved

Dear {{ $serviceRequest->client->firstName }},

Great news! Your project request "{{ $serviceRequest->project_name }}" has been approved.

**Project Details:**
- **Project Name:** {{ $serviceRequest->project_name }}
- **Service Type:** {{ $serviceRequest->service_type }}
- **Approved Budget:** ${{ number_format($serviceRequest->approved_budget, 2) }}
- **Payment Due Date:** {{ $serviceRequest->payment_due_date->format('M d, Y') }}

## Next Steps

To proceed with your project, please complete the payment using the details below:

**Payment Method:** {{ $serviceRequest->payment_method }}
**Amount:** ${{ number_format($serviceRequest->approved_budget, 2) }}
**Due Date:** {{ $serviceRequest->payment_due_date->format('M d, Y') }}

@if($serviceRequest->payment_instructions)
**Payment Instructions:**
{{ $serviceRequest->payment_instructions }}
@endif

<x-mail::button :url="route('client.requests.show', $serviceRequest->id)">
View Project Details
</x-mail::button>

Once your payment is confirmed, we will begin working on your project immediately.

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
