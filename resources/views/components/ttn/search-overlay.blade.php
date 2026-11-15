{{-- Full-screen search. Opens from the nav; results load as you type. Without JS the nav link goes to /search. --}}
<div class="ttn-search" data-search role="dialog" aria-modal="true" aria-label="Search" hidden>
    <div class="ttn-search__inner">
        <div class="ttn-search__top"><button class="ttn-search__close" type="button" aria-label="Close search" data-search-close><x-ttn.icon name="x" :size="26" /></button></div>
        <form action="{{ url('/search') }}" method="get" role="search">
            <label class="ttn-sr" for="ttn-search-input">Search</label>
            <input class="ttn-search__input" id="ttn-search-input" type="search" name="q" placeholder="Where to?" autocomplete="off" data-search-input>
        </form>
        <p class="ttn-search__hint" data-search-hint>Search stories, destinations and certified partners.</p>
        <div data-search-results aria-live="polite"></div>
    </div>
</div>
