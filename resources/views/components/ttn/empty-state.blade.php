{{-- Shown when a filter has nothing to show. Contains no gold by design. --}}
@props(['message' => 'Nothing here yet. The road is still being built.', 'actionLabel' => 'Clear filter', 'actionHref' => null])
<div {{ $attributes->class(['ttn-empty']) }}>
    <x-ttn.icon name="mountain" :size="32" />
    <p class="ttn-empty__message">{{ $message }}</p>
    @if ($actionHref)<x-ttn.button variant="secondary" size="sm" :href="$actionHref">{{ $actionLabel }}</x-ttn.button>@endif
</div>
