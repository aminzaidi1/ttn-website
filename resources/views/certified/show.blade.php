@php
    $hero = $partner->heroPhoto;
    $heroImage = $hero && ! $hero->isPlaceholder() ? $hero->url(1280) : null;
    $tel = $partner->phone ? preg_replace('/[^\d+]/', '', $partner->phone) : null;
    $websiteLabel = $partner->website ? rtrim(preg_replace('#^https?://(www\.)?#i', '', $partner->website), '/') : null;
    $contactHref = $partner->email ? 'mailto:'.$partner->email : ($tel ? 'tel:'.$tel : $partner->website);
    $gallery = $partner->galleryPhotos;
    $credit = $gallery->first()?->credit ?: ($hero?->credit ?: 'Photo: Waseem Photography');
    $placeholderNote = setting('partner.placeholder_note', '');

    $business = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $partner->name,
        'description' => $partner->description,
        'url' => $partner->url,
        'image' => $heroImage,
        'telephone' => $partner->phone,
        'email' => $partner->email,
        'sameAs' => $partner->website ? [$partner->website] : null,
        'address' => $partner->address ? ['@type' => 'PostalAddress', 'streetAddress' => $partner->address, 'addressCountry' => 'PK'] : null,
    ]);
    $breadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => setting('site.name', 'To The North'), 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => setting('certified.badge', 'TTN Certified'), 'item' => route('certified.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $partner->name, 'item' => $partner->url],
        ],
    ];
@endphp
<x-ttn.layout :title="$partner->seo_title ?: $partner->name" :description="$partner->seo_description ?: $partner->description" :image="$heroImage" :schema="[$business, $breadcrumbs]" progress active="/certified">
    <x-ttn.hero :photo="$hero" :title="$partner->name" page class="cert-hero">
        @if ($partner->category)
            <x-slot:before><div class="cert-hero__tag" data-rise><x-ttn.tag>{{ $partner->category->name }}</x-ttn.tag></div></x-slot:before>
        @endif
        <div class="cert-hero__foot" data-rise style="--rise-delay: 240ms">
            @if ($partner->location)<span class="ttn-meta"><x-ttn.icon name="mapPin" :size="14" />{{ $partner->location }}</span>@endif
            @if ($hero)<span class="ttn-credit">{{ $hero->credit }}</span>@endif
        </div>
    </x-ttn.hero>

    {{-- The signed certification block, then the overview: one Black section, the block is its gold. --}}
    <x-ttn.section class="cert-intro">
        @if ($partner->verdict)
            <div class="cert-block">
                <x-ttn.certification-block :since="$partner->since_label" :verdict="$partner->verdict" :signature="$partner->signature" />
            </div>
        @endif

        @if ($partner->overview)
            <div class="cert-article cert-overview" data-reveal>
                <div class="cert-overview__head">
                    <h2 class="ttn-h2">{{ setting('partner.overview_heading', 'Overview') }}</h2>
                    @if ($placeholderNote)<x-ttn.placeholder-chip :label="$placeholderNote" />@endif
                </div>
                <div class="ttn-prose">{!! $partner->overview !!}</div>
            </div>
        @endif
    </x-ttn.section>

    @if ($partner->offers->isNotEmpty())
        <x-ttn.section alt>
            <div class="cert-article">
                <h2 class="ttn-h2 cert-h2" data-reveal>{{ setting('partner.offers_heading', 'What they offer') }}</h2>
                <ul class="cert-offers" data-reveal-group>
                    @foreach ($partner->offers as $offer)
                        <li class="cert-offer" data-reveal><x-ttn.icon :name="$offer->icon" :size="20" /><span>{{ $offer->text }}</span></li>
                    @endforeach
                </ul>
            </div>
        </x-ttn.section>
    @endif

    @if ($gallery->isNotEmpty())
        <x-ttn.section class="cert-gallery-section">
            <div class="cert-gallery-head" data-reveal>
                <div class="ttn-eyebrow">{{ $partner->name }}</div>
                <h2 class="ttn-h2">{{ setting('partner.gallery_heading', 'On the ground') }}</h2>
            </div>
            <div class="cert-gallery" data-reveal-group>
                {{-- Staggered: one wide frame, then a pair. A last frame left alone runs wide. --}}
                @foreach ($gallery as $photo)
                    @php $wide = $loop->index % 3 === 0 || ($loop->last && $loop->index % 3 === 1); @endphp
                    <figure @class(['cert-gallery__item', 'cert-gallery__item--wide' => $wide]) data-reveal>
                        <x-ttn.photo :photo="$photo" :ratio="$wide ? '16/9' : '3/2'" class="cert-gallery__photo" :sizes="$wide ? '(min-width: 1280px) 1200px, 100vw' : '(min-width: 768px) 50vw, 100vw'" :width="$wide ? 1280 : 800" />
                        <figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />{{ $photo->caption }}</figcaption>
                    </figure>
                @endforeach
            </div>
            <p class="ttn-credit cert-credit">{{ $credit }}</p>
        </x-ttn.section>
    @endif

    {{-- Where to find them, closing on the contact call to action. Partner pages carry no map. --}}
    <x-ttn.section alt class="cert-contact-section">
        <div class="cert-article">
            <h2 class="ttn-h2 cert-h2" data-reveal>{{ setting('partner.contact_heading', 'Where to find them') }}</h2>
            <dl class="cert-contact" data-reveal>
                @if ($partner->address || $partner->map_note)
                    <div class="cert-contact__item">
                        <x-ttn.icon name="mapPin" :size="18" />
                        <div>
                            <dt class="cert-contact__label">{{ setting('partner.address_label', 'Address') }}</dt>
                            @if ($partner->address)<dd class="cert-contact__value">{{ $partner->address }}</dd>@endif
                            @if ($partner->map_note)<dd class="cert-contact__note">{{ $partner->map_note }}</dd>@endif
                        </div>
                    </div>
                @endif
                @if ($partner->phone)
                    <div class="cert-contact__item">
                        <x-ttn.icon name="phone" :size="18" />
                        <div>
                            <dt class="cert-contact__label">{{ setting('partner.phone_label', 'Phone') }}</dt>
                            <dd class="cert-contact__value"><a href="tel:{{ $tel }}">{{ $partner->phone }}</a></dd>
                        </div>
                    </div>
                @endif
                @if ($partner->email)
                    <div class="cert-contact__item">
                        <x-ttn.icon name="mail" :size="18" />
                        <div>
                            <dt class="cert-contact__label">{{ setting('partner.email_label', 'Email') }}</dt>
                            <dd class="cert-contact__value"><a href="mailto:{{ $partner->email }}">{{ $partner->email }}</a></dd>
                        </div>
                    </div>
                @endif
                @if ($partner->website)
                    <div class="cert-contact__item">
                        <x-ttn.icon name="globe" :size="18" />
                        <div>
                            <dt class="cert-contact__label">{{ setting('partner.website_label', 'Website') }}</dt>
                            <dd class="cert-contact__value"><a href="{{ $partner->website }}" target="_blank" rel="noopener">{{ $websiteLabel }}</a></dd>
                        </div>
                    </div>
                @endif
            </dl>
        </div>

        @if ($partner->cta_text || $contactHref)
            <div class="cert-cta" data-reveal>
                @if ($partner->cta_text)<p class="cert-cta__text">{{ $partner->cta_text }}</p>@endif
                @if ($contactHref)
                    <x-ttn.button variant="secondary" size="lg" icon="mail" :href="$contactHref">{{ str_replace(':name', $partner->name, setting('partner.contact_button', 'Contact :name')) }}</x-ttn.button>
                @endif
            </div>
        @endif
    </x-ttn.section>
</x-ttn.layout>
