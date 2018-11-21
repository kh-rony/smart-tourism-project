@component('mail::message')
# Hello, {{ $name }}

Thanks for registering to {{ config('app.name') }}.

@component('mail::button', ['url' => $route])
Verify Email
@endcomponent

@component('mail::panel')
    Click the button to verify your email.
@endcomponent

Regards,<br>
{{ config('app.name') }}
<hr>
@component('mail::footer')
    If you’re having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:{{ url($route) }}
@endcomponent
@endcomponent
