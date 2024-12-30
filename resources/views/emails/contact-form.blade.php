@component('mail::message')
# Nouveau message de contact

**De :** {{ $data['name'] }} ({{ $data['email'] }})  
**Sujet :** {{ $data['subject'] }}

## Message :
{{ $data['message'] }}

@component('mail::button', ['url' => config('app.url')])
Voir sur le site
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
