@php
    $title = setting('certified.title', 'Certified partners');
    $pills = [['label' => setting('certified.filter_all', 'All'), 'href' => route('certified.index'), 'active' => ! $active]];
    foreach ($categories as $category) {
        $pills[] = ['label' => $category->plural_name, 'href' => route('certified.index', ['category' => $category->slug]), 'active' => $active?->is($category)];
    }
    $sincePrefix = setting('certified.since_prefix', 'Certified since');
    $schema = [[
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $active ? $title.': '.$active->plural_name : $title,
        'itemListElement' => $partners->values()->map(fn ($p, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => $p->url, 'name' => $p->name])->all(),
    ]];
@endphp
<x-ttn.layout :title="$active ? $title.': '.$active->plural_name : $title" :description="setting('certified.seo_description', setting('certified.intro'))" :schema="$schema" active="/certified">
    <x-ttn.section>
        <header class="cert-head">
            <x-ttn.tag variant="certified">{{ setting('certified.badge', 'TTN Certified') }}</x-ttn.tag>
            <h1 class="ttn-h1 cert-head__title">{{ $title }}</h1>
            <p class="ttn-lede cert-head__intro">{{ setting('certified.intro', 'Businesses the To The North team has visited, vetted and stands behind. The gold mark means we trust them with your trip.') }}</p>
        </header>

        <div class="cert-criteria" data-reveal>
            <h2 class="ttn-eyebrow">{{ setting('certified.criteria_heading', 'What the mark means') }}</h2>
            <p class="cert-criteria__body">{{ setting('certified.criteria_body', 'Every certified business has been used by the TTN team on our own trips, unannounced and at full price. We check how they treat travellers when things go wrong, not just when they go right. Certification is reviewed yearly, cannot be bought, and is withdrawn if the standard slips.') }}</p>
        </div>

        <x-ttn.filter-pills :items="$pills" label="Filter partners by category" class="cert-pills" />

        @if ($partners->isNotEmpty())
            <ul class="cert-list" data-reveal-group>
                @foreach ($partners as $partner)
                    <li data-reveal>
                        <a class="cert-row" href="{{ $partner->url }}">
                            <x-ttn.photo :photo="$partner->thumbPhoto" ratio="1/1" class="cert-row__thumb" sizes="80px" :width="480" tiny alt="" />
                            <div class="cert-row__body">
                                <div class="cert-row__top">
                                    <h3 class="cert-row__name">{{ $partner->name }}</h3>
                                    @if ($partner->category)<x-ttn.tag>{{ $partner->category->name }}</x-ttn.tag>@endif
                                </div>
                                <div class="cert-row__meta">
                                    @if ($partner->location)<span class="ttn-meta"><x-ttn.icon name="mapPin" :size="13" />{{ $partner->location }}</span>@endif
                                    @if ($partner->since_label)<span class="ttn-meta">{{ $sincePrefix }} {{ $partner->since_label }}</span>@endif
                                </div>
                            </div>
                            <x-ttn.icon name="arrowRight" :size="18" class="cert-row__arrow" />
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <x-ttn.empty-state :message="setting('certified.empty', 'No certified partners in this category yet. We only list who we have vetted.')" :actionLabel="setting('certified.empty_action', 'Clear filter')" :actionHref="route('certified.index')" />
        @endif
    </x-ttn.section>
</x-ttn.layout>
