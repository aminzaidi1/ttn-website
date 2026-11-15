{{-- Schematic map: a server-rendered port of the kit's DDMap. Projects the real lat/lng of the
     starting point and the destination onto an 800x450 board (equirectangular, longitude scaled
     by the cosine of the mid latitude, fitted inside a 120 unit margin). Origin ring, dashed route,
     and the gold pin, which is the section's one gold element. Labels are HTML laid over the
     board so they stay legible at phone width. Without origin coordinates it shows the pin only;
     without destination coordinates it renders nothing. --}}
@props(['destination'])
@php
    $hasDest = $destination->lat !== null && $destination->lng !== null;
@endphp
@if ($hasDest)
@php
    $W = 800; $H = 450; $PAD = 120;
    $d = [(float) $destination->lat, (float) $destination->lng];
    $o = ($destination->origin_lat !== null && $destination->origin_lng !== null)
        ? [(float) $destination->origin_lat, (float) $destination->origin_lng] : null;
    $originName = $o ? ($destination->origin_name ?: null) : null;

    if ($o) {
        $midLat = deg2rad(($o[0] + $d[0]) / 2);
        $px = fn ($lon) => $lon * cos($midLat);
        $xs = [$px($o[1]), $px($d[1])];
        $ys = [$o[0], $d[0]];
        $spanX = max(abs($xs[1] - $xs[0]), 0.05);
        $spanY = max(abs($ys[1] - $ys[0]), 0.05);
        $k = min(($W - 2 * $PAD) / $spanX, ($H - 2 * $PAD) / $spanY);
        $cx = ($xs[0] + $xs[1]) / 2;
        $cy = ($ys[0] + $ys[1]) / 2;
        $X = fn ($lon) => $W / 2 + ($px($lon) - $cx) * $k;
        $Y = fn ($lat) => $H / 2 - ($lat - $cy) * $k;
        [$x1, $y1, $x2, $y2] = [$X($o[1]), $Y($o[0]), $X($d[1]), $Y($d[0])];
        $mx = ($x1 + $x2) / 2 - ($y2 - $y1) * 0.18;
        $my = ($y1 + $y2) / 2 + ($x2 - $x1) * 0.18;
        $originBelow = $y1 >= $y2;
    } else {
        [$x2, $y2] = [$W / 2, $H / 2];
        $originBelow = false;
    }

    $r = fn ($n) => round($n, 1);
    $pct = fn ($n, $of) => round($n / $of * 100, 3).'%';
    $fmt = fn ($v, $pos, $neg) => number_format(abs($v), 2).'° '.($v >= 0 ? $pos : $neg);
    $coords = $fmt($d[0], 'N', 'S').', '.$fmt($d[1], 'E', 'W');
    $caption = collect([
        $destination->name,
        $coords,
        $originName ? setting('destination.map_route_from', 'route from').' '.$originName : null,
        setting('destination.map_note', 'schematic, not to scale'),
    ])->filter()->implode(' · ');
@endphp
<figure {{ $attributes->class(['dest-map']) }}>
    <div class="dest-map__board">
        <svg class="dest-map__svg" viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
            <rect class="dest-map__bg" width="{{ $W }}" height="{{ $H }}" />
            <g class="dest-map__grid">
                <path d="M0 90 H800" /><path d="M0 225 H800" /><path d="M0 360 H800" />
                <path d="M160 0 V450" /><path d="M400 0 V450" /><path d="M640 0 V450" />
            </g>
            @if ($o)
                <path class="dest-map__route" d="M {{ $r($x1) }} {{ $r($y1) }} Q {{ $r($mx) }} {{ $r($my) }} {{ $r($x2) }} {{ $r($y2) }}" />
                <circle class="dest-map__origin" cx="{{ $r($x1) }}" cy="{{ $r($y1) }}" r="5" />
            @endif
            <g class="dest-map__pin">
                <circle class="dest-map__pin-ring" cx="{{ $r($x2) }}" cy="{{ $r($y2) }}" r="14" />
                <circle class="dest-map__pin-dot" cx="{{ $r($x2) }}" cy="{{ $r($y2) }}" r="6" />
            </g>
        </svg>
        @if ($originName)
            <span @class(['dest-map__label', 'dest-map__label--origin', 'is-below' => $originBelow, 'is-above' => ! $originBelow])
                style="left: {{ $pct($x1, $W) }}; top: {{ $pct($y1, $H) }}" aria-hidden="true">{{ $originName }}</span>
        @endif
        <span @class(['dest-map__label', 'dest-map__label--dest', 'is-above' => $originBelow, 'is-below' => ! $originBelow])
            style="left: {{ $pct($x2, $W) }}; top: {{ $pct($y2, $H) }}" aria-hidden="true">{{ $destination->name }}</span>
    </div>
    <figcaption class="dest-map__caption">{{ $caption }}</figcaption>
</figure>
@endif
