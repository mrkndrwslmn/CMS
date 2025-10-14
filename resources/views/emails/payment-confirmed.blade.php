<x-mail::message>
# Payment Confirmed

Dear {{ $serviceRequest->client->firstName }},

Thank you! Your payment for project "{{ $serviceRequest->project_name }}" has been confirmed.

**Payment Details:**
- **Amount:** ${{ number_format($serviceRequest->approved_budget, 2) }}
- **Payment Reference:** {{ $serviceRequest->payment_reference }}
- **Confirmed Date:** {{ $serviceRequest->payment_confirmed_at->format('M d, Y') }}

## What's Next?

Your project is now in progress! Our team will begin working on your project immediately. You can track the progress and updates in your client dashboard.

<x-mail::button :url="route('client.dashboard')">
View Dashboard
</x-mail::button>

We'll keep you updated on the progress and notify you of any important milestones.

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>