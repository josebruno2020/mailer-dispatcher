@props(['date'])

@if ($date)
    {{ $date->setTimezone(session('timezone', 'America/Sao_Paulo'))->format('d/m/Y H:i:s') }}
@else
    -
@endif
