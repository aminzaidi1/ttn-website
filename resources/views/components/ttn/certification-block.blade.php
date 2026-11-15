{{-- The signed TTN endorsement. Border and badge together are the section's one gold element. --}}
@props(['since' => null, 'verdict', 'signature' => 'The To The North team'])
<div {{ $attributes->class(['ttn-cert']) }}>
    <div class="ttn-cert__head">
        <x-ttn.tag variant="certified">TTN Certified</x-ttn.tag>
        @if ($since)<span class="ttn-cert__since">Certified since {{ $since }}</span>@endif
    </div>
    <p class="ttn-cert__verdict">{{ $verdict }}</p>
    <div class="ttn-cert__signature">{{ $signature }}</div>
</div>
