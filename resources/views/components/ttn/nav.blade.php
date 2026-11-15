@props(['active' => null])
@php
    $links = \App\Models\NavItem::for('header');
    $path = '/'.ltrim(request()->path(), '/');
    $isActive = function (string $url) use ($active, $path) {
        $u = '/'.trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        if ($active !== null) return $u === $active;
        return $u === '/' ? $path === '/' : ($path === $u || str_starts_with($path, $u.'/'));
    };
@endphp
<header class="ttn-nav" data-nav>
    <div class="ttn-inner ttn-nav__bar">
        <a class="ttn-nav__logo" href="{{ url('/') }}" aria-label="To The North, home">
            <picture>
                <source type="image/webp" srcset="{{ asset('img/logo-mountain.webp') }} 1x, {{ asset('img/logo-mountain@2x.webp') }} 2x">
                <img src="{{ asset('img/logo-mountain.png') }}" srcset="{{ asset('img/logo-mountain@2x.png') }} 2x" width="152" height="96" alt="To The North">
            </picture>
        </a>
        <nav class="ttn-nav__links" aria-label="Main">
            @foreach ($links as $link)
                <a href="{{ url($link['url']) }}" @class(['ttn-nav__link', 'is-active' => $isActive($link['url'])]) @if($isActive($link['url'])) aria-current="page" @endif>{{ $link['label'] }}</a>
            @endforeach
        </nav>
        <div class="ttn-nav__actions">
            <a class="ttn-nav__icon-btn" href="{{ url('/search') }}" aria-label="Search" data-search-open><x-ttn.icon name="search" :size="22" /></a>
            <x-ttn.button class="ttn-nav__subscribe" variant="secondary" size="sm" href="#newsletter">Subscribe</x-ttn.button>
            <button class="ttn-nav__icon-btn ttn-nav__burger" type="button" aria-label="Menu" aria-expanded="false" aria-controls="ttn-mobile-menu" data-nav-toggle>
                <x-ttn.icon name="menu" :size="24" class="ttn-icon--menu" /><x-ttn.icon name="x" :size="24" class="ttn-icon--close" />
            </button>
        </div>
    </div>
    <nav class="ttn-nav__mobile" id="ttn-mobile-menu" aria-label="Mobile">
        @foreach ($links as $link)
            <a href="{{ url($link['url']) }}" @class(['ttn-nav__mobile-link', 'is-active' => $isActive($link['url'])])>{{ $link['label'] }}</a>
        @endforeach
        <div class="ttn-nav__mobile-cta"><x-ttn.button variant="secondary" size="sm" href="#newsletter" data-nav-close>Subscribe</x-ttn.button></div>
    </nav>
</header>
