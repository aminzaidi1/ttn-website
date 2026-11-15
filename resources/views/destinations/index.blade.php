{{-- Destinations index: editorial rows, image and text alternating, region dividers between rows. --}}
@php
    $indexUrl = route('destinations.index');
    $pills = collect([['label' => setting('destinations.filter_all', 'All'), 'href' => $indexUrl, 'active' => $active === null]])
        ->concat($regions->map(fn ($r) => [
            'label' => $r->name,
            'href' => $indexUrl.'?region='.$r->slug,
            'active' => $active?->is($r) ?? false,
        ]))->all();

    $title = setting('destinations.title', 'Destinations');
    $schema = [[
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $active ? $title.' in '.$active->name : $title,
        'itemListElement' => $destinations->values()->map(fn ($d, $i) => [
            '@type' => 'ListItem', 'position' => $i + 1, 'url' => $d->url, 'name' => $d->name,
        ])->all(),
    ]];
    $cover = $destinations->first()?->cardPhoto;
@endphp
<x-ttn.layout
    :title="$active ? $title.' in '.$active->name : $title"
    :description="setting('destinations.seo_description', 'The valleys, lakes and passes of northern Pakistan worth the drive, with seasons, altitudes and drive times from the people who have driven them.')"
    :image="$cover && ! $cover->isPlaceholder() ? $cover->url(1280) : null"
    :schema="$schema"
    active="/destinations">
    <x-ttn.section class="dest-index">
        <header class="dest-index__head">
            <h1 class="ttn-h1">{{ $title }}</h1>
            <p class="dest-index__intro">{{ setting('destinations.intro', 'The valleys, lakes and passes worth the drive. Filter by region, then start planning.') }}</p>
        </header>

        <x-ttn.filter-pills class="dest-index__pills" :items="$pills" :label="setting('destinations.filter_label', 'Filter destinations by region')" />

        @if ($destinations->isEmpty())
            <x-ttn.empty-state
                :message="setting('destinations.empty', 'Nothing here yet. The road is still being built.')"
                :actionLabel="setting('destinations.empty_action', 'Clear filter')"
                :actionHref="$indexUrl" />
        @else
            <div class="dest-rows">
                @foreach ($destinations as $d)
                    @if (! $loop->first)
                        <x-ttn.divider :label="$d->region?->name" />
                    @endif
                    @php
                        $mini = array_values(array_filter([
                            ['icon' => 'calendar', 'label' => 'Best season', 'value' => $d->stat_season],
                            ['icon' => 'mountain', 'label' => 'Altitude', 'value' => $d->stat_altitude],
                            ['icon' => 'car', 'label' => 'Drive from Islamabad', 'value' => $d->stat_drive],
                        ], fn ($m) => filled($m['value'])));
                    @endphp
                    <article @class(['dest-row', 'dest-row--flip' => $loop->index % 2 === 1]) data-reveal>
                        <a class="dest-row__media" href="{{ $d->url }}" tabindex="-1" aria-hidden="true">
                            <x-ttn.photo :photo="$d->cardPhoto ?? $d->heroPhoto" ratio="3/2" sizes="(min-width: 1024px) 680px, 100vw" :alt="$d->name" />
                        </a>
                        <div class="dest-row__body">
                            @if ($d->region)<x-ttn.tag>{{ $d->region->name }}</x-ttn.tag>@endif
                            <h2 class="dest-row__name"><a href="{{ $d->url }}">{{ $d->name }}</a></h2>
                            @if ($d->blurb)<p class="dest-row__blurb">{{ $d->blurb }}</p>@endif
                            @if ($mini)
                                <ul class="dest-row__stats">
                                    @foreach ($mini as $m)
                                        <li><x-ttn.icon :name="$m['icon']" :size="15" /><span class="ttn-sr">{{ $m['label'] }}: </span>{{ $m['value'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <x-ttn.gold-link :href="$d->url">{{ str_replace(':name', $d->name, setting('destinations.explore_link', 'Explore :name')) }}</x-ttn.gold-link>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </x-ttn.section>
</x-ttn.layout>
