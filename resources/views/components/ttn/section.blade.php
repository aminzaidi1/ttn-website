@props(['alt' => false, 'width' => null])
<section {{ $attributes->class(['ttn-section', 'ttn-section--alt' => $alt]) }}>
    <div @class(['ttn-inner', 'ttn-inner--content' => $width === 'content', 'ttn-inner--article' => $width === 'article'])>{{ $slot }}</div>
</section>
