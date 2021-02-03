@component('mail::message')
# Test Failed
##

The body of your message. - {{$test->name}}

@component('mail::button', ['url' => config('app.client_url')])
View Logs
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
