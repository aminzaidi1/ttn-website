@props(['variant' => 'category', 'icon' => null])
<span {{ $attributes->class(['ttn-tag', 'ttn-tag--'.$variant]) }}>@if($icon)<x-ttn.icon :name="$icon" :size="12" />@endif{{ $slot }}</span>
