@php
    $placeholderNote = setting('about.placeholder_note', '');
    $statDefaults = [1 => ['466,000', 'Community members'], 2 => ['7', 'Years on the road'], 3 => ['6', 'Destinations documented'], 4 => ['3', 'Certified partners']];
    $stats = [];
    foreach ($statDefaults as $n => [$value, $label]) {
        $value = setting("about.stat{$n}_value", $value);
        // A plain number (466,000 or 466,000+) counts up once when scrolled into view.
        $count = preg_match('/^(\d[\d,]*)(\+?)$/', trim($value), $m) ? ['to' => str_replace(',', '', $m[1]), 'suffix' => $m[2]] : null;
        $stats[] = ['value' => $value, 'count' => $count, 'label' => setting("about.stat{$n}_label", $label)];
    }
    $credit = $gallery->first()?->photo?->credit ?: 'Photo: Waseem Photography';
@endphp
<x-ttn.layout :title="setting('about.page_title', 'About')" :description="setting('about.seo_description', setting('about.lead'))" :image="$portrait && ! $portrait->isPlaceholder() ? $portrait->url(1280) : null" active="/about">
    {{-- 1. Founder story --}}
    <x-ttn.section>
        <div class="ttn-split about-founder">
            <figure class="about-founder__portrait">
                <x-ttn.photo :photo="$portrait" ratio="3/4" class="about-photo" eager sizes="(min-width: 768px) 45vw, 100vw" :width="1280" note="Waseem in the field, camera in hand" />
                @if ($portrait?->caption)<figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />{{ $portrait->caption }}</figcaption>@endif
            </figure>
            <div class="about-founder__text">
                <div class="about-founder__eyebrow">
                    <span class="ttn-eyebrow about-gold">{{ setting('about.eyebrow', 'Our story') }}</span>
                    @if ($placeholderNote)<x-ttn.placeholder-chip :label="$placeholderNote" />@endif
                </div>
                <h1 class="ttn-h1 about-founder__title">{{ setting('about.title', 'It started with a camera and a road.') }}</h1>
                <p class="about-founder__lead">{{ setting('about.lead', "To The North began in 2018 as one photographer's record of the northern valleys. Seven years later it is a community of hundreds of thousands, still run by people who load up the jeep and go.") }}</p>
                <p class="about-founder__body">{{ setting('about.body', 'We are not a travel agency. We are travellers who photograph what we find and tell you the truth about getting there.') }}</p>
            </div>
        </div>
    </x-ttn.section>

    {{-- 2. Stats band, on Deep between thin rules --}}
    <section class="about-stats">
        <div class="ttn-inner">
            <dl class="about-stats__grid" data-reveal-group>
                @foreach ($stats as $stat)
                    <div class="about-stat" data-reveal>
                        <dt class="about-stat__label">{{ $stat['label'] }}</dt>
                        <dd class="about-stat__value">
                            @if ($stat['count'])<span data-count="{{ $stat['count']['to'] }}" @if($stat['count']['suffix']) data-suffix="{{ $stat['count']['suffix'] }}" @endif>{{ $stat['value'] }}</span>@else{{ $stat['value'] }}@endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- 3. Vision and mission --}}
    <x-ttn.section>
        <div class="ttn-g2" data-reveal-group>
            <div class="about-vm" data-reveal>
                <h2 class="ttn-eyebrow">{{ setting('about.vision_heading', 'Vision') }}</h2>
                <p class="about-vm__body">{{ setting('about.vision', 'The north documented honestly, valley by valley, by the people who drive it.') }}</p>
            </div>
            <div class="about-vm" data-reveal>
                <h2 class="ttn-eyebrow">{{ setting('about.mission_heading', 'Mission') }}</h2>
                <p class="about-vm__body">{{ setting('about.mission', 'Promote tourism and mountaineering in the Northern Areas of Pakistan.') }}</p>
            </div>
        </div>
    </x-ttn.section>

    {{-- 4. Portfolio gallery --}}
    @if ($gallery->isNotEmpty())
        <x-ttn.section alt>
            <div class="about-gallery-head" data-reveal>
                <div class="ttn-eyebrow">{{ setting('about.gallery_eyebrow', 'The work') }}</div>
                <h2 class="ttn-h2">{{ setting('about.gallery_title', 'Seven years of the north') }}</h2>
            </div>
            <div class="about-gallery" data-reveal-group>
                {{-- Staggered: one wide frame, then a pair. A last frame left alone runs wide. --}}
                @foreach ($gallery as $item)
                    @php $wide = $loop->index % 3 === 0 || ($loop->last && $loop->index % 3 === 1); @endphp
                    <figure @class(['about-gallery__item', 'about-gallery__item--wide' => $wide]) data-reveal>
                        <x-ttn.photo :photo="$item->photo" :ratio="$wide ? '16/9' : '3/2'" class="about-photo" :sizes="$wide ? '(min-width: 1280px) 1200px, 100vw' : '(min-width: 768px) 50vw, 100vw'" :width="$wide ? 1280 : 800" />
                        @if ($item->photo?->caption)<figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />{{ $item->photo->caption }}</figcaption>@endif
                    </figure>
                @endforeach
            </div>
            <p class="ttn-credit about-credit">{{ $credit }}</p>
        </x-ttn.section>
    @endif

    {{-- 5. The team --}}
    @if ($team->isNotEmpty())
        <x-ttn.section>
            <x-ttn.section-header :title="setting('about.team_title', 'The team')" />
            <ul class="about-team" data-reveal-group>
                @foreach ($team as $member)
                    <li class="about-member" data-reveal>
                        <x-ttn.photo :photo="$member->photo" ratio="1/1" class="about-member__avatar" sizes="110px" :width="480" tiny :alt="$member->name" />
                        <div>
                            <h3 class="about-member__name">{{ $member->name }}</h3>
                            @if ($member->role_line)<div class="about-member__role">{{ $member->role_line }}</div>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-ttn.section>
    @endif
</x-ttn.layout>
