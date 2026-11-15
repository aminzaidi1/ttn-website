{{-- Newsletter signup. Variants: embedded (full section), footer (compact), inline (end of a story).
     A real form: works without JavaScript, upgraded in place when JS is available. --}}
@props(['variant' => 'embedded', 'source' => null, 'eyebrow' => null, 'headline' => null, 'subline' => null, 'buttonLabel' => 'Subscribe'])
@php
    $compact = in_array($variant, ['footer', 'inline']);
    $source ??= $variant;
    $uid = 'news-'.$variant.'-'.\Illuminate\Support\Str::random(4);
    $done = session('newsletter_status') === 'success';
    $failed = session('newsletter_status') === 'error' || $errors->has('email');
@endphp
<div {{ $attributes->class(['ttn-news', 'ttn-news--'.$variant]) }} data-newsletter>
    @if ($variant === 'embedded')
    <div class="ttn-news__inner">
        <div class="ttn-eyebrow ttn-eyebrow--light">{{ $eyebrow ?? setting('newsletter.eyebrow', 'The TTN Newsletter') }}</div>
        <h2 class="ttn-news__headline">{{ $headline ?? setting('newsletter.headline', 'The north, delivered to your inbox') }}</h2>
        <p class="ttn-news__subline">{{ $subline ?? setting('newsletter.subline', 'One email a week. New stories, destinations and certified partners.') }}</p>
    @elseif ($variant === 'inline')
        <p class="ttn-news__prompt">{{ setting('newsletter.inline_prompt', 'Enjoyed this story? Get more like it every week.') }}</p>
    @else
        <span class="ttn-news__prompt">{{ setting('newsletter.footer_prompt', 'Get the weekly dispatch from the north') }}</span>
    @endif
        <div class="ttn-news__field">
            <div class="ttn-news__success" data-newsletter-success @unless($done) hidden @endunless role="status">
                <x-ttn.icon name="check" :size="20" />{{ setting('newsletter.success', "You're in. See you in the north.") }}
            </div>
            <form method="post" action="{{ url('/newsletter') }}" @class(['ttn-news-form', 'ttn-news-form--compact' => $compact, 'is-error' => $failed]) @if($done) hidden @endif novalidate>
                @csrf
                <input type="hidden" name="source" value="{{ $source }}">
                <label class="ttn-sr" for="{{ $uid }}">Email address</label>
                <input class="ttn-news-form__input" id="{{ $uid }}" type="email" name="email" placeholder="you@email.com" autocomplete="email" inputmode="email" required>
                <input class="ttn-news-form__trap" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <x-ttn.button type="submit" variant="primary" :size="$compact ? 'sm' : 'md'">{{ $buttonLabel }}</x-ttn.button>
            </form>
            <div class="ttn-news__error" data-newsletter-error @unless($failed) hidden @endunless role="alert">Please enter a valid email address.</div>
        </div>
    @if ($variant === 'embedded')</div>@endif
</div>
