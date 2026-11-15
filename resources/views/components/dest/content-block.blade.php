{{-- One "What to do" block, in the order the editor set: kind label, heading, text. No gold. --}}
@props(['block'])
@php
    $paras = collect(preg_split('/\R{2,}/', trim((string) $block->body)))->filter(fn ($p) => trim($p) !== '');
@endphp
<div {{ $attributes->class(['dest-block']) }}>
    @if ($block->kind)<div class="dest-block__kind">{{ $block->kind }}</div>@endif
    <div class="dest-block__body">
        @if ($block->heading)<h3 class="dest-block__heading">{{ $block->heading }}</h3>@endif
        @foreach ($paras as $p)
            <p class="dest-block__text">{!! nl2br(e(trim($p))) !!}</p>
        @endforeach
    </div>
</div>
