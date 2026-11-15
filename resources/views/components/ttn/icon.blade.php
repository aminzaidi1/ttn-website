@props(['name', 'size' => 20, 'stroke' => 2])
<svg {{ $attributes->class(['ttn-icon']) }} xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! \App\Support\Icons::PATHS[$name] ?? '' !!}</svg>
