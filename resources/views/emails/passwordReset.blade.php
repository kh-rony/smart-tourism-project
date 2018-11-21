@component('mail::message')
# Hello, {{ $name }}

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $route])
Reset Password
@endcomponent

@component('mail::panel')
    If you did not request a password reset, no further action is required.
@endcomponent

Regards,<br>
{{ config('app.name') }}
<hr>
@component('mail::footer')
    If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:{{ url($route) }}
@endcomponent
@endcomponent
