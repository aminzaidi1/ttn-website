@php
    $photo = $story->heroPhoto ?? $story->cardPhoto;
    $shareImage = $photo && ! $photo->isPlaceholder() ? $photo->url(1280) : null;
    $author = $story->author;
    $credit = $photo?->credit ?: 'Photo: Waseem Photography';
    $description = $story->seo_description ?: ($story->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $story->lead), 300));
    $schema = [
        array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $story->title,
            'description' => $description,
            'url' => $story->url,
            'mainEntityOfPage' => $story->url,
            'image' => $shareImage,
            'datePublished' => $story->published_at?->toAtomString(),
            'dateModified' => $story->updated_at?->toAtomString(),
            'articleSection' => $story->category?->name,
            'contentLocation' => $story->location ? ['@type' => 'Place', 'name' => $story->location] : null,
            'author' => $author ? ['@type' => 'Person', 'name' => $author->name] : null,
            'publisher' => ['@type' => 'Organization', 'name' => setting('site.name', 'To The North'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('icon-512.png')]],
        ]),
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => setting('site.name', 'To The North'), 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => setting('stories.title', 'Stories'), 'item' => route('stories.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $story->title, 'item' => $story->url],
            ],
        ],
    ];
@endphp
<x-ttn.layout
    :title="$story->seo_title ?: $story->title"
    :description="$description"
    :image="$shareImage"
    type="article"
    :schema="$schema"
    progress
    active="/stories">
    <x-slot:head>
        @if ($story->published_at)<meta property="article:published_time" content="{{ $story->published_at->toAtomString() }}">@endif
        @if ($story->category)<meta property="article:section" content="{{ $story->category->name }}">@endif
    </x-slot:head>

    <x-ttn.hero class="story-hero" page :photo="$photo" :title="$story->title">
        <x-slot:before>
            @if ($story->category)<div class="story-hero__tag" data-rise><x-ttn.tag variant="gold">{{ $story->category->name }}</x-ttn.tag></div>@endif
        </x-slot:before>
    </x-ttn.hero>

    <div class="story-meta">
        <div class="ttn-inner ttn-inner--article story-meta__inner">
            <div class="story-meta__facts">
                @if ($author)<span>{{ $author->name }}</span><span aria-hidden="true">·</span>@endif
                @if ($story->published_at)<time datetime="{{ $story->published_at->toDateString() }}">{{ $story->date_label }}</time>@endif
                @if ($story->read_time)<span aria-hidden="true">·</span><span class="story-meta__item"><x-ttn.icon name="clock" :size="13" />{{ $story->read_time }}</span>@endif
                @if ($story->location)<span class="story-meta__sep" aria-hidden="true">·</span><span class="story-meta__item story-meta__loc"><x-ttn.icon name="mapPin" :size="13" />{{ $story->location }}</span>@endif
            </div>
            <span class="story-meta__credit">{{ $credit }}</span>
        </div>
    </div>

    <article class="story-article">
        <div class="ttn-inner ttn-inner--article">
            @if ($story->lead)<p class="story-lead">{{ $story->lead }}</p>@endif
            <div class="ttn-prose story-body">{!! $story->body !!}</div>

            <x-ttn.newsletter-signup class="story-news" variant="inline" source="story" />

            @if ($author)
            <aside class="story-author" aria-label="About the author">
                <x-ttn.photo class="story-author__photo" :photo="$author->photo" ratio="1/1" :width="480" sizes="72px" :alt="$author->name" tiny />
                <div class="story-author__text">
                    <div class="story-author__name">{{ $author->name }}</div>
                    @if ($author->bio)<p class="story-author__bio">{{ $author->bio }}</p>@endif
                    <a class="story-author__more" href="{{ $next?->url ?? route('stories.index') }}">{{ setting('stories.more_by', 'More stories by') }} {{ $author->name }}</a>
                </div>
            </aside>
            @endif
        </div>
    </article>
</x-ttn.layout>
