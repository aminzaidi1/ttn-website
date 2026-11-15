@php
    $social = array_filter([
        'facebook' => setting('social.facebook'),
        'instagram' => setting('social.instagram'),
        'tiktok' => setting('social.tiktok'),
        'youtube' => setting('social.youtube'),
    ]);
@endphp
<footer class="ttn-footer" id="newsletter">
    <div class="ttn-inner ttn-footer__inner">
        <div class="ttn-footer__cols">
            <div class="ttn-footer__brand">
                <picture>
                    <source type="image/webp" srcset="{{ asset('img/logo-mountain.webp') }} 1x, {{ asset('img/logo-mountain@2x.webp') }} 2x">
                    <img src="{{ asset('img/logo-mountain.png') }}" srcset="{{ asset('img/logo-mountain@2x.png') }} 2x" width="152" height="96" alt="To The North" loading="lazy">
                </picture>
                <p class="ttn-footer__mission">{{ setting('footer.mission', 'Promote tourism and mountaineering in the Northern Areas of Pakistan.') }}</p>
                @if ($social)
                <div class="ttn-footer__social">
                    @foreach ($social as $name => $url)
                        <a href="{{ $url }}" aria-label="{{ ucfirst($name) }}" target="_blank" rel="noopener"><x-ttn.icon :name="$name" :size="18" /></a>
                    @endforeach
                </div>
                @endif
            </div>
            <div>
                <h2 class="ttn-footer__head">{{ setting('footer.explore_heading', 'Explore') }}</h2>
                <ul class="ttn-footer__list">
                    @foreach (\App\Models\NavItem::for('footer_explore') as $link)<li><a href="{{ url($link['url']) }}">{{ $link['label'] }}</a></li>@endforeach
                </ul>
            </div>
            <div>
                <h2 class="ttn-footer__head">{{ setting('footer.community_heading', 'Community') }}</h2>
                <ul class="ttn-footer__list">
                    @foreach (\App\Models\NavItem::for('footer_community') as $link)<li><a href="{{ \Illuminate\Support\Str::startsWith($link['url'], 'http') ? $link['url'] : url($link['url']) }}" @if(\Illuminate\Support\Str::startsWith($link['url'], 'http')) target="_blank" rel="noopener" @endif>{{ $link['label'] }}</a></li>@endforeach
                </ul>
            </div>
            <div>
                <h2 class="ttn-footer__head">{{ setting('footer.newsletter_heading', 'The TTN Newsletter') }}</h2>
                <x-ttn.newsletter-signup variant="footer" source="footer" />
            </div>
        </div>
        <x-ttn.divider style="margin-top: var(--space-6)" />
        <div class="ttn-footer__base">
            <span>© {{ setting('footer.copyright', 'To The North · Lahore, Pakistan') }}</span>
            <span>{{ setting('footer.members_line', '466,000 travellers and counting') }}</span>
        </div>
        <div class="ttn-footer__base" style="padding-top: 0">
            <span>{{ setting('footer.credit', 'All photography by Waseem Photography.') }}</span>
            <span class="ttn-footer__legal"><a href="{{ url('/privacy') }}">Privacy</a><a href="{{ url('/terms') }}">Terms</a></span>
        </div>
    </div>
</footer>
