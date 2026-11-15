{{-- A photo from the library, or its labelled placeholder slot while the real frame is still to come.
     ratio: CSS aspect ratio such as "16/9". fill: cover the parent instead. --}}
@props(['photo' => null, 'ratio' => '16/9', 'fill' => false, 'sizes' => '100vw', 'eager' => false, 'code' => null, 'note' => null, 'alt' => null, 'tiny' => false, 'width' => 800])
@php
    $isReal = $photo && ! $photo->isPlaceholder();
    $slotCode = $photo?->slot_code ?: ($code ?: 'Photo to come');
    $slotNote = $photo?->slot_note ?: ($note ?: $photo?->caption);
    $altText = $alt ?? ($photo?->alt_text ?? '');
@endphp
<div {{ $attributes->class(['ttn-photo', 'ttn-photo--fill' => $fill]) }} @unless($fill) style="aspect-ratio: {{ $ratio }}" @endunless>
@if ($isReal)
    <img src="{{ $photo->url($width) }}" @if($photo->srcset()) srcset="{{ $photo->srcset() }}" sizes="{{ $sizes }}" @endif alt="{{ $altText }}" @if($photo->width) width="{{ $photo->width }}" height="{{ $photo->height }}" @endif @if($eager) fetchpriority="high" decoding="sync" @else loading="lazy" decoding="async" @endif>
@else
    <div @class(['ttn-slot', 'ttn-slot--tiny' => $tiny]) role="img" aria-label="Placeholder: {{ $altText ?: $slotNote }}">
        <span class="ttn-slot__code">{{ $slotCode }}</span>
        @if ($slotNote)<span class="ttn-slot__note">{{ $slotNote }}</span>@endif
    </div>
@endif
</div>
