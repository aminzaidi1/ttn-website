{{-- TTN Certified partner card. The gold border is the certified mark. --}}
@props(['partner' => null, 'name' => null, 'category' => null, 'location' => null, 'description' => null, 'ctaLabel' => 'View partner', 'href' => '#'])
@php
    if ($partner) {
        $name ??= $partner->name;
        $category ??= $partner->category?->name;
        $location ??= $partner->location;
        $description ??= $partner->description;
        $href = $partner->url;
    }
@endphp
<article {{ $attributes->class(['ttn-partner-card']) }}>
    <div class="ttn-partner-card__badge"><x-ttn.tag variant="certified">TTN Certified</x-ttn.tag></div>
    <div>
        <h3 class="ttn-partner-card__name"><a href="{{ $href }}">{{ $name }}</a></h3>
        @if ($category)<div class="ttn-partner-card__category">{{ $category }}</div>@endif
    </div>
    @if ($location)<div class="ttn-meta"><x-ttn.icon name="mapPin" :size="14" />{{ $location }}</div>@endif
    @if ($description)<p class="ttn-partner-card__desc">{{ $description }}</p>@endif
    <div class="ttn-partner-card__cta"><x-ttn.button variant="secondary" size="sm" :href="$href">{{ $ctaLabel }}</x-ttn.button></div>
</article>
