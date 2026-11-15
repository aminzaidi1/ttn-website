{{-- Destination detail. Kit order: hero, stats bar, road status, sticky sub-nav, overview,
     getting there (route legs + schematic map), where to stay, what to do, photo strip,
     community tips; closes on certified partners, then related destinations. --}}
@php
    $d = $destination;
    $region = $d->region;
    $hero = $d->heroPhoto;
    $realHero = $hero && ! $hero->isPlaceholder();
    $hasMap = $d->lat !== null && $d->lng !== null;

    $has = [
        'overview' => filled(strip_tags((string) $d->overview)) || filled($d->pull_quote),
        'getting-there' => filled(strip_tags((string) $d->getting_there)) || $d->routeLegs->isNotEmpty() || $hasMap,
        'stay' => filled(strip_tags((string) $d->where_to_stay)),
        'todo' => $d->blocks->isNotEmpty(),
        'strip' => $d->stripPhotos->isNotEmpty(),
        'tips' => $communityPosts->isNotEmpty(),
        'partners' => $partners->isNotEmpty(),
        'related' => $related->isNotEmpty(),
    ];

    $headings = [
        'overview' => setting('destination.overview_heading', 'Overview'),
        'getting-there' => setting('destination.getting_there_heading', 'Getting there'),
        'stay' => setting('destination.stay_heading', 'Where to stay'),
        'todo' => setting('destination.todo_heading', 'What to do'),
        'tips' => setting('destination.tips_heading', 'Community tips'),
    ];
    $subnav = collect($headings)->filter(fn ($label, $id) => $has[$id])
        ->map(fn ($label, $id) => ['id' => $id, 'label' => $label])->values()->all();

    // Overview: the pull quote sits after the first paragraph, as in the kit.
    $overview = (string) $d->overview;
    $cut = stripos($overview, '</p>');
    if ($cut !== false && $d->pull_quote) {
        $overviewFirst = substr($overview, 0, $cut + 4);
        $overviewRest = trim(substr($overview, $cut + 4));
    } else {
        $overviewFirst = $overview;
        $overviewRest = '';
    }

    // Sections alternate Black and Deep, whichever of them this destination has.
    $surfaces = [];
    $alt = false;
    foreach (['overview', 'getting-there', 'stay', 'todo', 'strip', 'tips', 'partners', 'related'] as $key) {
        if ($has[$key]) { $surfaces[$key] = $alt; $alt = ! $alt; }
    }

    $description = $d->seo_description ?: ($d->blurb ?: \Illuminate\Support\Str::limit(strip_tags($overview), 160));
    $place = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'TouristDestination',
        'name' => $d->name,
        'description' => $description,
        'url' => $d->url,
        'image' => $realHero ? $hero->url(1280) : null,
        'geo' => $hasMap ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $d->lat, 'longitude' => (float) $d->lng] : null,
        'containedInPlace' => $region ? ['@type' => 'AdministrativeArea', 'name' => $region->name.', Pakistan'] : null,
    ]);
    $breadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => setting('site.name', 'To The North'), 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => setting('destinations.title', 'Destinations'), 'item' => route('destinations.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $d->name, 'item' => $d->url],
        ],
    ];
@endphp
<x-ttn.layout
    :title="$d->seo_title ?: $d->name"
    :description="$description"
    :image="$realHero ? $hero->url(1280) : null"
    :schema="[$place, $breadcrumbs]"
    progress
    active="/destinations">

    {{-- Hero: the gold region tag is its one gold element; the name sits lower-left. --}}
    <x-ttn.hero class="dest-hero" page :photo="$hero" :title="$d->name">
        @if ($region)
            <x-slot:before><div class="dest-hero__tag" data-rise><x-ttn.tag variant="region">{{ $region->name }}</x-ttn.tag></div></x-slot:before>
        @endif
        <div class="dest-hero__foot" data-rise style="--rise-delay: 200ms">
            <span class="ttn-meta"><x-ttn.icon name="mapPin" :size="14" />{{ $hero?->caption ?: $d->name.($region ? ', '.$region->name : '') }}</span>
            <span class="ttn-credit">{{ $hero?->credit ?: setting('destination.photo_credit', 'Photo: Waseem Photography') }}</span>
        </div>
    </x-ttn.hero>

    <x-dest.stats-bar :stats="array_values($d->stats())" />
    <x-dest.road-status :destination="$d" />
    <x-dest.sub-nav :items="$subnav" :label="setting('destination.subnav_label', 'On this page')" />

    @if ($has['overview'])
        <x-ttn.section id="overview" :alt="$surfaces['overview']" width="article" class="dest-sec">
            <h2 class="ttn-h2 dest-sec__title" data-reveal>{{ $headings['overview'] }}</h2>
            @if (filled(strip_tags($overviewFirst)))<div class="ttn-prose" data-reveal>{!! $overviewFirst !!}</div>@endif
            @if ($d->pull_quote)
                <blockquote class="ttn-pullquote dest-pull" data-reveal><p>{{ $d->pull_quote }}</p></blockquote>
            @endif
            @if (filled(strip_tags($overviewRest)))<div class="ttn-prose" data-reveal>{!! $overviewRest !!}</div>@endif
        </x-ttn.section>
    @endif

    @if ($has['getting-there'])
        <x-ttn.section id="getting-there" :alt="$surfaces['getting-there']" width="article" class="dest-sec">
            <h2 class="ttn-h2 dest-sec__title" data-reveal>{{ $headings['getting-there'] }}</h2>
            @if (filled(strip_tags((string) $d->getting_there)))<div class="ttn-prose" data-reveal>{!! $d->getting_there !!}</div>@endif
            <x-dest.route-legs :legs="$d->routeLegs" data-reveal />
            <x-dest.simple-map :destination="$d" data-reveal />
        </x-ttn.section>
    @endif

    @if ($has['stay'])
        <x-ttn.section id="stay" :alt="$surfaces['stay']" width="article" class="dest-sec">
            <h2 class="ttn-h2 dest-sec__title" data-reveal>{{ $headings['stay'] }}</h2>
            <div class="ttn-prose" data-reveal>{!! $d->where_to_stay !!}</div>
        </x-ttn.section>
    @endif

    @if ($has['todo'])
        <x-ttn.section id="todo" :alt="$surfaces['todo']" width="article" class="dest-sec">
            <h2 class="ttn-h2 dest-sec__title dest-sec__title--lg" data-reveal>{{ $headings['todo'] }}</h2>
            <div class="dest-blocks">
                @foreach ($d->blocks as $block)
                    <x-dest.content-block :block="$block" data-reveal />
                @endforeach
            </div>
        </x-ttn.section>
    @endif

    @if ($has['strip'])
        <section @class(['dest-strip-sec', 'ttn-section--alt' => $surfaces['strip']]) aria-label="{{ $d->name }} in photographs">
            <x-dest.photo-strip :photos="$d->stripPhotos" data-reveal="fade" />
        </section>
    @endif

    @if ($has['tips'])
        <x-ttn.section id="tips" :alt="$surfaces['tips']" class="dest-tips">
            <div class="dest-tips__head" data-reveal>
                <div class="ttn-eyebrow">{{ setting('destination.tips_eyebrow', 'From the group') }}</div>
                <h2 class="ttn-h2">{{ $headings['tips'] }}</h2>
            </div>
            <div class="ttn-g2" data-reveal-group>
                @foreach ($communityPosts as $post)
                    <x-ttn.community-quote :post="$post" data-reveal />
                @endforeach
            </div>
        </x-ttn.section>
    @endif

    @if ($has['partners'])
        <x-ttn.section :alt="$surfaces['partners']" class="dest-close">
            <h2 class="ttn-h2 dest-sec__title dest-sec__title--lg" data-reveal>{{ str_replace(':region', $region?->name ?? $d->name, setting('destination.partners_heading', 'TTN Certified in :region')) }}</h2>
            <div class="ttn-g3" data-reveal-group>
                @foreach ($partners as $partner)
                    <x-ttn.partner-card :partner="$partner" :ctaLabel="setting('destination.partner_cta', 'View partner')" data-reveal />
                @endforeach
            </div>
        </x-ttn.section>
    @endif

    @if ($has['related'])
        <x-ttn.section :alt="$surfaces['related']" class="dest-close">
            <h2 class="ttn-h2 dest-sec__title dest-sec__title--lg" data-reveal>{{ setting('destination.related_heading', 'Keep exploring') }}</h2>
            <div class="ttn-g3" data-reveal-group>
                @foreach ($related as $other)
                    <x-ttn.destination-card :destination="$other" data-reveal />
                @endforeach
            </div>
        </x-ttn.section>
    @endif
</x-ttn.layout>
