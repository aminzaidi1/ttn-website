@props(['title', 'action' => null, 'href' => null])
<div {{ $attributes->class(['ttn-section-header']) }} data-reveal>
    <h2 class="ttn-h2">{{ $title }}</h2>
    @if ($action && $href)<x-ttn.button variant="text" :href="$href" iconRight="arrowRight">{{ $action }}</x-ttn.button>@endif
</div>
