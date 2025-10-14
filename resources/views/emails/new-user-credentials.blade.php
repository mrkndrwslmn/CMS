<x-mail::message>
# Welcome to TreisAdiutor, {{ $fullName }}!

Thank you for submitting your service request. We've automatically created an account for you so you can track your request and communicate with us.

## Your Login Credentials

**Email:** {{ $email }}  
**Password:** {{ $password }}

Please keep these credentials safe. We recommend changing your password after your first login.

<x-mail::button :url="url('/login')">
Login to Your Account
</x-mail::button>

You can now:
- Track your service request status
- Upload additional documents
- Communicate with your assigned Adiutor
- View reports and updates

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>