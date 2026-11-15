{{-- Ordered legs of the drive: from, to, distance, time and a short note. No gold. --}}
@props(['legs'])
@if ($legs->isNotEmpty())
<ol {{ $attributes->class(['dest-legs']) }}>
    @foreach ($legs as $leg)
        <li class="dest-legs__leg">
            <div class="dest-legs__head">
                <div class="dest-legs__route">
                    <span>{{ $leg->from_place }}</span>
                    <x-ttn.icon name="arrowRight" :size="14" />
                    <span class="ttn-sr">to</span>
                    <span>{{ $leg->to_place }}</span>
                </div>
                @if ($leg->distance || $leg->duration)
                    <div class="dest-legs__dist">{{ collect([$leg->distance, $leg->duration])->filter()->implode(' · ') }}</div>
                @endif
            </div>
            @if ($leg->body)<p class="dest-legs__note">{{ $leg->body }}</p>@endif
        </li>
    @endforeach
</ol>
@endif
