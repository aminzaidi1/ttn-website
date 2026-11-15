@php($drafts = $this->getDrafts())
<x-filament-widgets::widget>
    <x-filament::section heading="Drafts" description="Unpublished stories, destinations and partners, most recently edited first.">
        @if ($drafts->isEmpty())
            <p style="font-size: .875rem; opacity: .7;">Nothing waiting. Everything is published.</p>
        @else
            <div style="display: flex; flex-direction: column;">
                @foreach ($drafts as $draft)
                    @unless ($loop->first)
                        <hr style="border: 0; border-top: 1px solid; opacity: .15; margin: 0;">
                    @endunless
                    <a href="{{ $draft['url'] }}" style="display: flex; align-items: center; gap: 1rem; padding: .7rem 0;">
                        <span style="width: 9rem; flex-shrink: 0;"><x-filament::badge color="gray">{{ $draft['type'] }}</x-filament::badge></span>
                        <span style="flex: 1; font-size: .875rem; font-weight: 500;">{{ $draft['title'] }}</span>
                        <span style="flex-shrink: 0; font-size: .75rem; opacity: .6;">Edited {{ $draft['edited']?->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
