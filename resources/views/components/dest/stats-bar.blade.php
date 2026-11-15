{{-- At-a-glance strip under the destination hero. Only filled stats are passed in.
     Two columns on phones, all of them in one row on desktop. No gold. --}}
@props(['stats' => []])
@if (count($stats))
<div {{ $attributes->class(['dest-stats']) }}>
    <dl class="ttn-inner dest-stats__grid" style="--dest-stats-n: {{ count($stats) }}">
        @foreach ($stats as $stat)
            <div class="dest-stats__item">
                <x-ttn.icon :name="$stat['icon']" :size="20" />
                <div>
                    <dt class="dest-stats__label">{{ $stat['label'] }}</dt>
                    <dd class="dest-stats__value">{{ $stat['value'] }}</dd>
                </div>
            </div>
        @endforeach
    </dl>
</div>
@endif
