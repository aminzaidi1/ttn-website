{{-- Story card. Tag is always the grey pill: gold is reserved for the single featured story. --}}
@props(['story' => null, 'photo' => null, 'tag' => null, 'title' => null, 'excerpt' => null, 'author' => null, 'date' => null, 'readTime' => null, 'href' => '#'])
@php
    if ($story) {
        $photo ??= $story->cardPhoto;
        $tag ??= $story->category?->name;
        $title ??= $story->title;
        $excerpt ??= $story->excerpt;
        $author ??= $story->author?->name;
        $date ??= $story->date_label;
        $readTime ??= $story->read_time;
        $href = $story->url;
    }
@endphp
<a href="{{ $href }}" {{ $attributes->class(['ttn-story-card']) }}>
    <x-ttn.photo :photo="$photo" ratio="16/9" sizes="(min-width: 1024px) 400px, (min-width: 768px) 50vw, 100vw" :alt="$title" />
    <div class="ttn-story-card__body">
        @if ($tag)<div class="ttn-story-card__tag"><x-ttn.tag>{{ $tag }}</x-ttn.tag></div>@endif
        <h3 class="ttn-story-card__title">{{ $title }}</h3>
        @if ($excerpt)<p class="ttn-story-card__excerpt">{{ $excerpt }}</p>@endif
        <div class="ttn-story-card__meta">
            @if ($author)<span>{{ $author }}</span>@endif
            @if ($author && $date)<span aria-hidden="true">·</span>@endif
            @if ($date)<span>{{ $date }}</span>@endif
            @if ($readTime)<span class="ttn-story-card__time"><x-ttn.icon name="clock" :size="13" />{{ $readTime }}</span>@endif
        </div>
    </div>
</a>
