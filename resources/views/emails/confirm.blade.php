@component('mail::message')
# Hello {{$user->name}}

You changed your email, so please verify this email using the Button bellow:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
    Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent