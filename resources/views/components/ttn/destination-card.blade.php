{{-- Destination card. Region is the grey pill: the gold region tag belongs to the destination hero only. --}}
@props(['destination' => null, 'photo' => null, 'name' => null, 'region' => null, 'href' => '#'])
@php
    if ($destination) {
        $photo ??= $destination->cardPhoto;
        $name ??= $destination->name;
        $region ??= $destination->region?->name;
        $href = $destination->url;
    }
@endphp
<a href="{{ $href }}" {{ $attributes->class(['ttn-dest-card']) }}>
    <x-ttn.photo :photo="$photo" fill sizes="(min-width: 1024px) 400px, (min-width: 768px) 50vw, 100vw" :alt="$name" />
    <div class="ttn-dest-card__shade"></div>
    @if ($region)<div class="ttn-dest-card__region"><x-ttn.tag>{{ $region }}</x-ttn.tag></div>@endif
    <h3 class="ttn-dest-card__name">{{ $name }}</h3>
</a>
