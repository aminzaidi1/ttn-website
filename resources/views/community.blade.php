@php
    $placeholderNote = setting('community.placeholder_note', '');
    $viewLabel = setting('community.view_on_facebook', 'View on Facebook');
    $group = setting('social.facebook_group', 'https://facebook.com/share/g/1D3ztF2tAq/');
@endphp
<x-ttn.layout :title="setting('community.title', 'Community')" :description="setting('community.seo_description', setting('community.intro'))" active="/community">
    <x-ttn.section>
        <header class="comm-head">
            <h1 class="ttn-h1">{{ setting('community.title', 'Community') }}</h1>
            <p class="ttn-lede comm-head__intro">{{ setting('community.intro', 'The road is better shared. These are the people who actually drove it, and what they brought back.') }}</p>
        </header>

        @if ($posts->isNotEmpty())
            <div class="comm-label">
                <h2 class="ttn-eyebrow">{{ setting('community.featured_label', 'Featured from the community') }}</h2>
                @if ($placeholderNote)<x-ttn.placeholder-chip :label="$placeholderNote" />@endif
            </div>
            <div class="comm-masonry" data-reveal-group>
                @foreach ($posts as $post)
                    <a class="comm-card" href="{{ $post->facebook_url }}" target="_blank" rel="noopener noreferrer" data-reveal>
                        @if ($post->photo)
                            <x-ttn.photo :photo="$post->photo" ratio="16/9" sizes="(min-width: 1024px) 400px, (min-width: 768px) 50vw, 100vw" alt="" />
                        @endif
                        <div class="comm-card__body">
                            <p class="comm-card__quote">{{ $post->quote }}</p>
                            <div class="comm-card__by">
                                <span class="comm-card__name">{{ $post->member_name }}</span>
                                @if ($post->posted_on)<span aria-hidden="true">·</span><time datetime="{{ $post->posted_on->toDateString() }}">{{ $post->date_label }}</time>@endif
                            </div>
                            <div class="comm-card__link"><x-ttn.icon name="externalLink" :size="13" />{{ $viewLabel }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <x-ttn.empty-state />
        @endif
    </x-ttn.section>

    <x-ttn.section alt>
        <div class="comm-join" data-reveal>
            <h2 class="comm-join__title">{{ setting('community.join_heading', '466,000 travellers and counting') }}</h2>
            <p class="comm-join__body">{{ setting('community.join_body', 'Built over seven years on Facebook. Ask a question, share a route, or just post the photo you cannot stop looking at.') }}</p>
            <x-ttn.button variant="primary" size="lg" icon="facebook" :href="$group" target="_blank" rel="noopener">{{ setting('community.join_button', 'Join the Facebook group') }}</x-ttn.button>
        </div>
    </x-ttn.section>
</x-ttn.layout>
