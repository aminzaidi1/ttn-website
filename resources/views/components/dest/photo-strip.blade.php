{{-- Full-bleed horizontal strip of photographs, held still. Each caption names the location. --}}
@props(['photos', 'credit' => null])
@if ($photos->isNotEmpty())
<div {{ $attributes->class(['dest-strip']) }}>
    <ul class="dest-strip__track" tabindex="0" aria-label="Photographs">
        @foreach ($photos as $photo)
            <li class="dest-strip__item">
                <figure>
                    <x-ttn.photo :photo="$photo" ratio="3/2" sizes="(min-width: 768px) 440px, 78vw" />
                    @if ($photo->caption)
                        <figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />{{ $photo->caption }}</figcaption>
                    @endif
                </figure>
            </li>
        @endforeach
    </ul>
    <p class="ttn-inner ttn-credit dest-strip__credit">{{ $credit ?? ($photos->first()->credit ?: setting('destination.photo_credit', 'Photo: Waseem Photography')) }}</p>
</div>
@endif
