{{-- Base layout for every public page: head, SEO tags, nav, footer, search overlay. --}}
@props([
    'title' => null,          // page title, without the site name
    'description' => null,
    'canonical' => null,
    'image' => null,          // absolute URL for social sharing
    'type' => 'website',      // website | article
    'schema' => [],           // extra JSON-LD blocks (arrays)
    'progress' => false,      // reading progress line, article-type pages only
    'noindex' => false,
    'active' => null,         // nav item to mark active, e.g. "/stories"
])
@php
    $siteName = setting('site.name', 'To The North');
    $fullTitle = $title ? $title.' · '.$siteName : $siteName.' · '.setting('site.tagline', "Pakistan's northern tourism community");
    $description = \Illuminate\Support\Str::limit(strip_tags($description ?: setting('site.description', 'Stories, destinations and trusted partners from the north of Pakistan, by the community that has been driving these roads since 2018.')), 300);
    $canonical ??= url()->current();
    $image ??= asset('img/og-default.png');
    $baseSchema = [
        ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => $siteName, 'url' => url('/'), 'logo' => asset('icon-512.png'),
            'foundingDate' => '2018', 'sameAs' => array_values(array_filter([setting('social.facebook'), setting('social.facebook_group'), setting('social.instagram'), setting('social.youtube'), setting('social.tiktok')]))],
        ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => $siteName, 'url' => url('/'),
            'potentialAction' => ['@type' => 'SearchAction', 'target' => url('/search').'?q={search_term_string}', 'query-input' => 'required name=search_term_string']],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">
    @if ($noindex)<meta name="robots" content="noindex, nofollow">@endif
    <meta name="theme-color" content="#0A0A0A">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $title ?: $siteName }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:locale" content="en_PK">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} stories" href="{{ url('/feed.xml') }}">
    <link rel="preload" href="/fonts/inter-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/playfair-display-latin-900-normal.woff2" as="font" type="font/woff2" crossorigin>
    <script>document.documentElement.classList.add('js-motion')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @foreach (array_merge($baseSchema, $schema) as $block)
    <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach
    {{ $head ?? '' }}
</head>
<body>
    <a class="ttn-skip" href="#main">Skip to content</a>
    @if ($progress)<div class="ttn-progress" aria-hidden="true"><div class="ttn-progress__bar" data-progress></div></div>@endif
    <x-ttn.nav :active="$active" />
    <main id="main">{{ $slot }}</main>
    <x-ttn.footer />
    <x-ttn.search-overlay />
</body>
</html>
