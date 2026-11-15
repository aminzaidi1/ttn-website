<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Region;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::orderBy('order')->orderBy('name')->get();

        $active = null;
        if ($slug = $request->query('region')) {
            $active = $regions->firstWhere('slug', $slug);
            abort_if($active === null, 404);
        }

        $destinations = Destination::published()
            ->with(['region', 'cardPhoto', 'heroPhoto'])
            ->when($active, fn ($q) => $q->where('region_id', $active->id))
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('destinations.index', compact('regions', 'active', 'destinations'));
    }

    public function show(Destination $destination)
    {
        abort_unless($destination->is_published, 404);

        $destination->load([
            'region',
            'heroPhoto',
            'blocks',
            'routeLegs',
            'stripPhotos',
        ]);

        $communityPosts = $destination->communityPosts()->published()
            ->with('photo')
            ->orderBy('order')
            ->orderByDesc('posted_on')
            ->get();

        $partners = $destination->partners()->published()
            ->with(['category', 'region'])
            ->get();

        // Other published destinations, the same region first.
        $related = Destination::published()
            ->whereKeyNot($destination->id)
            ->with(['region', 'cardPhoto'])
            ->orderByRaw('CASE WHEN region_id = ? THEN 0 ELSE 1 END', [$destination->region_id])
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('destinations.show', compact('destination', 'communityPosts', 'partners', 'related'));
    }
}
