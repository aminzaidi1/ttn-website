{{-- Filter pills as real links, so every filtered view has its own shareable URL.
     items: [['label' => 'All', 'href' => '...', 'active' => true], ...] --}}
@props(['items' => [], 'label' => 'Filter'])
<nav {{ $attributes->class(['ttn-pills']) }} aria-label="{{ $label }}">
    @foreach ($items as $item)
        <a href="{{ $item['href'] }}" @class(['ttn-pill', 'is-active' => $item['active'] ?? false]) @if($item['active'] ?? false) aria-current="true" @endif>{{ $item['label'] }}</a>
    @endforeach
</nav>
