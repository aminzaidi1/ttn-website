<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerCategory;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /** TTN Certified: the registry of vetted partners, filterable by category (?category=slug). */
    public function index(Request $request)
    {
        $categories = PartnerCategory::orderBy('order')->get();
        $active = null;

        if ($slug = $request->query('category')) {
            $active = $categories->firstWhere('slug', $slug);
            abort_unless($active, 404);
        }

        $partners = Partner::published()
            ->with(['category', 'thumbPhoto'])
            ->when($active, fn ($q) => $q->where('partner_category_id', $active->id))
            ->orderBy('order')
            ->get();

        return view('certified.index', compact('categories', 'active', 'partners'));
    }

    /** One certified partner. Unpublished partners do not exist for visitors. */
    public function show(Partner $partner)
    {
        abort_unless($partner->is_published, 404);

        $partner->load(['category', 'heroPhoto', 'offers', 'galleryPhotos']);

        return view('certified.show', compact('partner'));
    }
}
