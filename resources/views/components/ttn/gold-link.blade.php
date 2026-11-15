@props(['href' => '#', 'icon' => 'arrowRight'])
<a href="{{ $href }}" {{ $attributes->class(['ttn-gold-link']) }}>{{ $slot }}@if($icon)<x-ttn.icon :name="$icon" :size="16" />@endif</a>
