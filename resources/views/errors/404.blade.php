@php
    $photo = \App\Models\Photo::slot('404');
@endphp
<x-ttn.layout :title="setting('notfound.page_title', 'Page not found')" noindex>
    {{-- No caption on this image by design: the page is fictional wayfinding. --}}
    <section class="misc-404">
        <x-ttn.photo :photo="$photo" fill eager sizes="100vw" :width="1920" alt="" />
        <div class="misc-404__shade"></div>
        <div class="misc-404__content">
            <div class="ttn-eyebrow">{{ setting('notfound.eyebrow', '404') }}</div>
            <h1 class="misc-404__title">{{ setting('notfound.title', 'This road does not go anywhere.') }}</h1>
            <p class="misc-404__body">{{ setting('notfound.body', 'The page you are looking for has washed out, slid away, or never existed. Head back and take the other fork.') }}</p>
            <div class="misc-404__action"><x-ttn.button variant="secondary" :href="url('/')">{{ setting('notfound.button', 'Back to the homepage') }}</x-ttn.button></div>
        </div>
    </section>
</x-ttn.layout>
