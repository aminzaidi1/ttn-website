{{-- Full-bleed hero. Only the standard dark gradient sits over the photograph.
     The CTA is the hero's one gold element. --}}
@props(['photo' => null, 'eyebrow' => null, 'title', 'sub' => null, 'ctaLabel' => null, 'ctaHref' => null, 'location' => null, 'page' => false, 'code' => null, 'note' => null])
<section {{ $attributes->class(['ttn-hero', 'ttn-hero--page' => $page]) }}>
    <x-ttn.photo :photo="$photo" fill eager sizes="100vw" :width="1920" :code="$code" :note="$note" alt="" />
    <div class="ttn-hero__shade"></div>
    <div class="ttn-inner ttn-hero__content">
        @if ($eyebrow)<div class="ttn-eyebrow ttn-eyebrow--light ttn-hero__eyebrow" data-rise>{{ $eyebrow }}</div>@endif
        @isset($before){{ $before }}@endisset
        <h1 class="ttn-hero__title" data-rise style="--rise-delay: 80ms">{{ $title }}</h1>
        @if ($sub)<p class="ttn-hero__sub" data-rise style="--rise-delay: 160ms">{{ $sub }}</p>@endif
        @if ($ctaLabel && $ctaHref)<div class="ttn-hero__cta" data-rise style="--rise-delay: 240ms"><x-ttn.button variant="primary" size="lg" :href="$ctaHref" iconRight="arrowRight">{{ $ctaLabel }}</x-ttn.button></div>@endif
        {{ $slot }}
        @if ($location)<div class="ttn-meta ttn-hero__location" data-rise style="--rise-delay: 320ms"><x-ttn.icon name="mapPin" :size="14" />{{ $location }}</div>@endif
    </div>
</section>
