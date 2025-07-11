<x-mail::message>
# {{ $subject }}

{!! $content !!}

Thanks,<br>
{{ config('app.name') }}

<x-mail::subcopy>
If you no longer wish to receive these emails, you can <a href="{{ route('newsletter.unsubscribe', ['email' => $email, 'token' => $token]) }}">unsubscribe here</a>.
</x-mail::subcopy>
</x-mail::message>