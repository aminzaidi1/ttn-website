{{-- Sticky in-page navigation under the main nav. app.js ([data-spy]) marks the current link
     and scrolls the strip sideways on phones. Pass only sections that exist.
     items: [['id' => 'overview', 'label' => 'Overview'], ...] --}}
@props(['items' => [], 'label' => 'On this page'])
@if (count($items) > 1)
<div {{ $attributes->class(['dest-subnav']) }}>
    <nav class="ttn-inner dest-subnav__links" data-spy aria-label="{{ $label }}">
        @foreach ($items as $item)
            <a class="dest-subnav__link" href="#{{ $item['id'] }}">{{ $item['label'] }}</a>
        @endforeach
    </nav>
</div>
@endif
