@component('mail::message')
# Welcome {{ $user->name }}

Your account has been created successfully.

**Login Email:** {{ $user->email }}  
**Default Password:** {{ $password }}

@component('mail::button', ['url' => url('/')])
Login Now
@endcomponent

Please change your password after logging in.

Thanks,  
{{ config('app.name') }}
@endcomponent
