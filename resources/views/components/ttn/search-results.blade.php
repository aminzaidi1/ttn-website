{{-- Grouped search results, used by the overlay and the /search page. --}}
@props(['results' => [], 'query' => ''])
@php $total = collect($results)->flatten(1)->count(); @endphp
@foreach (['Stories' => 'stories', 'Destinations' => 'destinations', 'Partners' => 'partners'] as $label => $key)
    @if (! empty($results[$key]))
        <div class="ttn-eyebrow ttn-search__group">{{ $label }}</div>
        @foreach ($results[$key] as $row)
            <a class="ttn-search__row" href="{{ $row['url'] }}"><span class="ttn-search__title">{{ $row['title'] }}</span><span class="ttn-search__meta">{{ $row['meta'] }}</span></a>
        @endforeach
    @endif
@endforeach
@if ($query !== '' && $total === 0)
    <x-ttn.empty-state />
@endif
