@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'icon' => null, 'iconRight' => null, 'disabled' => false, 'type' => 'button'])
@php
    $classes = ['ttn-btn', 'ttn-btn--'.$variant, 'ttn-btn--sm' => $size === 'sm', 'ttn-btn--lg' => $size === 'lg'];
    $iconSize = $size === 'lg' ? 18 : 16;
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class($classes) }} @if($disabled) aria-disabled="true" tabindex="-1" @endif>@if($icon)<x-ttn.icon :name="$icon" :size="$iconSize" />@endif{{ $slot }}@if($iconRight)<x-ttn.icon :name="$iconRight" :size="$iconSize" />@endif</a>
@else
<button type="{{ $type }}" {{ $attributes->class($classes) }} @disabled($disabled)>@if($icon)<x-ttn.icon :name="$icon" :size="$iconSize" />@endif{{ $slot }}@if($iconRight)<x-ttn.icon :name="$iconRight" :size="$iconSize" />@endif</button>
@endif
