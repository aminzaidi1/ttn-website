{{-- Featured community post: a real Facebook post, credited, linking out to the original. --}}
@props(['post' => null, 'quote' => null, 'name' => null, 'photo' => null, 'date' => null, 'href' => null])
@php
    if ($post) {
        $quote ??= $post->quote;
        $name ??= $post->member_name;
        $photo ??= $post->photo;
        $date ??= $post->date_label;
        $href ??= $post->facebook_url;
    }
@endphp
<figure {{ $attributes->class(['ttn-quote-card']) }}>
    @if ($photo)<x-ttn.photo :photo="$photo" ratio="16/9" sizes="(min-width: 1024px) 400px, 100vw" />@endif
    <div class="ttn-quote-card__body">
        <div class="ttn-quote-card__source"><x-ttn.icon name="facebook" :size="14" />Featured from our community</div>
        <blockquote class="ttn-quote-card__quote">{{ $quote }}</blockquote>
        <figcaption class="ttn-quote-card__foot">
            <span class="ttn-quote-card__name">{{ $name }}, To The North Community{{ $date ? ' · '.$date : '' }}</span>
            @if ($href)<a class="ttn-quote-card__link" href="{{ $href }}" target="_blank" rel="noopener">View on Facebook<x-ttn.icon name="externalLink" :size="13" /></a>@endif
        </figcaption>
    </div>
</figure>
