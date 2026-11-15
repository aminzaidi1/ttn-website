<x-ttn.layout :title="$query !== '' ? 'Search: '.$query : 'Search'" noindex>
    <x-ttn.section width="article">
        <form action="{{ url('/search') }}" method="get" role="search">
            <label class="ttn-sr" for="search-page-input">Search</label>
            <input class="ttn-search__input" id="search-page-input" type="search" name="q" value="{{ $query }}" placeholder="Where to?" autocomplete="off">
        </form>
        @if ($query === '')
            <p class="ttn-search__hint">Search stories, destinations and certified partners.</p>
        @else
            <x-ttn.search-results :results="$results" :query="$query" />
        @endif
    </x-ttn.section>
</x-ttn.layout>
