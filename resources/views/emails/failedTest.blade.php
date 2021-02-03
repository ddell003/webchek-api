@component('mail::message')
# {{$test->site->name}} - Test Failed!
##

The test {{$test->name}} failed

@component('mail::button', ['url' => config('app.client_url')])
View Logs
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
