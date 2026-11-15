@php
    $pageTitle = setting('stories.title', 'Stories');
    $pills = collect([['label' => setting('stories.filter_all', 'All'), 'href' => route('stories.index'), 'active' => ! $region]])
        ->concat($regions->map(fn ($r) => ['label' => $r->name, 'href' => route('stories.index', ['region' => $r->slug]), 'active' => $region?->is($r)]))
        ->all();
    $listed = collect($featured ? [$featured] : [])->concat($rest->items());
    $schema = [];
    if ($listed->isNotEmpty()) {
        $schema[] = [
            '@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => $region ? $pageTitle.', '.$region->name : $pageTitle,
            'itemListElement' => $listed->values()->map(fn ($s, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => $s->url, 'name' => $s->title])->all(),
        ];
    }
    $leadPhoto = $featured ? ($featured->cardPhoto ?? $featured->heroPhoto) : null;
@endphp
<x-ttn.layout
    :title="$region ? $pageTitle.', '.$region->name : $pageTitle"
    :description="setting('stories.description', 'Dispatches from the road, the trail and the camp: trip reports, road trips and photography from the north of Pakistan.')"
    :canonical="$region ? route('stories.index', ['region' => $region->slug]) : route('stories.index')"
    :noindex="! $hasStories"
    :schema="$schema"
    active="/stories">
    <x-ttn.section>
        <header class="story-index__head">
            <h1 class="ttn-h1">{{ $pageTitle }}</h1>
            <p class="ttn-lede story-index__intro">{{ setting('stories.intro', 'Dispatches from the road, the trail and the camp.') }}</p>
        </header>

        <x-ttn.filter-pills class="story-index__pills" :items="$pills" label="Filter stories by region" />

        @unless ($hasStories)
            <x-ttn.empty-state
                :message="setting('stories.empty', 'Nothing here yet. The road is still being built.')"
                :actionLabel="setting('stories.empty_action', 'Clear filter')"
                :actionHref="route('stories.index')" />
        @else
            @if ($featured)
            <article class="story-feature" data-reveal>
                <a class="story-feature__media" href="{{ $featured->url }}" tabindex="-1" aria-hidden="true">
                    <x-ttn.photo :photo="$leadPhoto" fill sizes="(min-width: 1024px) 60vw, 100vw" :width="1280" :alt="$featured->title" />
                </a>
                <div class="story-feature__body">
                    @if ($featured->category)<x-ttn.tag variant="gold">{{ $featured->category->name }}</x-ttn.tag>@endif
                    <h2 class="story-feature__title"><a href="{{ $featured->url }}">{{ $featured->title }}</a></h2>
                    @if ($featured->excerpt)<p class="story-feature__excerpt">{{ $featured->excerpt }}</p>@endif
                    <div class="story-feature__meta">
                        @if ($featured->author)<span>{{ $featured->author->name }}</span><span aria-hidden="true">·</span>@endif
                        <span>{{ $featured->date_label }}</span>
                        @if ($featured->read_time)<span aria-hidden="true">·</span><span class="story-feature__time"><x-ttn.icon name="clock" :size="13" />{{ $featured->read_time }}</span>@endif
                    </div>
                    <a class="story-feature__link" href="{{ $featured->url }}">{{ setting('stories.read_story', 'Read story') }}<x-ttn.icon name="arrowRight" :size="15" /></a>
                </div>
            </article>
            @endif

            @if ($rest->isNotEmpty())
                <x-ttn.divider class="story-index__divider" :label="setting('stories.latest_label', 'Latest')" />
                <div class="ttn-g3" data-reveal-group>
                    @foreach ($rest as $story)
                        <x-ttn.story-card :story="$story" data-reveal />
                    @endforeach
                </div>
            @endif

            @if ($rest->hasPages())
            <nav class="story-pager" aria-label="Pagination">
                @if ($rest->previousPageUrl())
                    <a class="story-pager__link" href="{{ $rest->previousPageUrl() }}" rel="prev"><x-ttn.icon name="arrowLeft" :size="16" />{{ setting('stories.newer', 'Newer stories') }}</a>
                @else
                    <span></span>
                @endif
                @if ($rest->nextPageUrl())
                    <a class="story-pager__link" href="{{ $rest->nextPageUrl() }}" rel="next">{{ setting('stories.older', 'Older stories') }}<x-ttn.icon name="arrowRight" :size="16" /></a>
                @endif
            </nav>
            @endif
        @endunless
    </x-ttn.section>
</x-ttn.layout>
