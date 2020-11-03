@component('mail::message')
# Hello {{$user->name}}

Thank you for created an account. Please verify your email using the Button bellow:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent