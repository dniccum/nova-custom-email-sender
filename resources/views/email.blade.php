@component('mail::message')

    {!! $content !!}

    {{-- Salutation --}}
    @if (! empty($salutation))
        {!! $salutation !!}
    @endif
@endcomponent
