@php
    $heroImage = $heroPhoto && ! $heroPhoto->isPlaceholder() ? $heroPhoto->url(1280) : null;
    $members = setting('site.members', '466,000');
    $schema = [];
    if ($stories->isNotEmpty()) {
        $schema[] = [
            '@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => setting('home.stories_title', 'Latest stories'),
            'itemListElement' => $stories->values()->map(fn ($s, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => $s->url, 'name' => $s->title])->all(),
        ];
    }
@endphp
<x-ttn.layout :image="$heroImage" :schema="$schema" active="/">
    <x-ttn.hero
        :photo="$heroPhoto"
        code="HOME-01"
        :eyebrow="setting('home.hero_eyebrow', 'Northern Pakistan')"
        :title="setting('home.hero_title', 'The mountains are waiting. So is the road.')"
        :sub="setting('home.hero_sub', 'Seven years of stories, destinations and photography from the people who actually drove the KKH.')"
        :ctaLabel="$lead ? setting('home.hero_cta', 'Read the story') : null"
        :ctaHref="$lead?->url"
        :location="$heroPhoto?->caption ?: 'Attabad Lake, Gilgit-Baltistan'"
    />

    @if ($stories->isNotEmpty())
    <x-ttn.section>
        <x-ttn.section-header :title="setting('home.stories_title', 'Latest stories')" :action="setting('home.stories_action', 'View all stories')" :href="route('stories.index')" />
        <div class="ttn-g3" data-reveal-group>
            @foreach ($stories as $story)
                <x-ttn.story-card :story="$story" data-reveal />
            @endforeach
        </div>
    </x-ttn.section>
    @endif

    @if ($destinations->isNotEmpty())
    <x-ttn.section alt>
        <x-ttn.section-header :title="setting('home.destinations_title', 'Explore destinations')" :action="setting('home.destinations_action', 'All destinations')" :href="route('destinations.index')" />
        <div class="ttn-g3 home-dests" data-reveal-group>
            @foreach ($destinations as $destination)
                <x-ttn.destination-card :destination="$destination" data-reveal />
            @endforeach
        </div>
    </x-ttn.section>
    @endif

    @if ($posts->isNotEmpty())
    <x-ttn.section>
        <x-ttn.section-header :title="setting('home.community_title', 'Featured from the community')" />
        <div class="ttn-g2 home-posts" data-reveal-group>
            @foreach ($posts as $post)
                <x-ttn.community-quote :post="$post" data-reveal />
            @endforeach
        </div>
    </x-ttn.section>
    @endif

    <x-ttn.newsletter-signup variant="embedded" source="home" data-reveal />

    @if ($partners->isNotEmpty())
    <x-ttn.section>
        <x-ttn.section-header :title="setting('home.partners_title', 'TTN Certified partners')" :action="setting('home.partners_action', 'See all partners')" :href="route('certified.index')" />
        <div class="ttn-g3" data-reveal-group>
            @foreach ($partners as $partner)
                <x-ttn.partner-card :partner="$partner" data-reveal />
            @endforeach
        </div>
    </x-ttn.section>
    @endif

    <x-ttn.section alt>
        <div class="home-join" data-reveal>
            <h2 class="home-join__title">{{ $members }} {{ setting('home.join_title', 'travellers and counting') }}</h2>
            <p class="home-join__body">{{ setting('home.join_body', 'Join the largest community of northern Pakistan travellers, built over seven years on Facebook.') }}</p>
            <x-ttn.button variant="primary" size="lg" icon="facebook" :href="setting('social.facebook_group', 'https://facebook.com/share/g/1D3ztF2tAq/')" target="_blank" rel="noopener">{{ setting('home.join_cta', 'Join the community') }}</x-ttn.button>
        </div>
    </x-ttn.section>
</x-ttn.layout>
