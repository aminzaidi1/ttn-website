{{-- Dated road conditions. Fail-safe: renders nothing at all without both text and a date.
     When present it always carries the date and the standing line. No gold. --}}
@props(['destination'])
@if ($destination->hasRoadStatus())
@php $group = setting('social.facebook_group'); @endphp
<div {{ $attributes->class(['dest-road']) }} role="note">
    <div class="ttn-inner dest-road__inner">
        <x-ttn.icon name="car" :size="18" />
        <div>
            <p class="dest-road__text">{{ $destination->road_status_text }}</p>
            <p class="dest-road__meta">
                {{ str_replace(':date', $destination->road_status_as_of->format('j F Y'), setting('destination.road_status_as_of', 'Road status as of :date.')) }}
                {{ setting('destination.road_status_note', 'Conditions change fast.') }}
                @if ($group)
                    <a href="{{ $group }}" target="_blank" rel="noopener">{{ setting('destination.road_status_link', 'Check the group before you drive.') }}</a>
                @else
                    {{ setting('destination.road_status_link', 'Check the group before you drive.') }}
                @endif
            </p>
        </div>
    </div>
</div>
@endif
