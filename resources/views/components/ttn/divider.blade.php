@props(['label' => null])
@if ($label)
<div {{ $attributes->class(['ttn-divider--labelled']) }} role="separator"><span class="ttn-divider__label">{{ $label }}</span></div>
@else
<hr {{ $attributes->class(['ttn-divider']) }}>
@endif
