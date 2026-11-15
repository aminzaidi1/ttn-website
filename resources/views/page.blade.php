<x-ttn.layout :title="$page->title" :description="$page->seo_description">
    <x-ttn.section width="article">
        <h1 class="ttn-h1" style="margin-bottom: var(--space-4)">{{ $page->title }}</h1>
        <div class="ttn-prose">{!! $page->body !!}</div>
    </x-ttn.section>
</x-ttn.layout>
