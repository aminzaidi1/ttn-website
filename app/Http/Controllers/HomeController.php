<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\Destination;
use App\Models\Partner;
use App\Models\Photo;
use App\Models\Story;

class HomeController extends Controller
{
    public function __invoke()
    {
        $stories = Story::published()
            ->with(['category', 'author', 'cardPhoto'])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // The hero button opens the featured story, or the latest one when none is featured.
        $lead = Story::published()->where('is_featured', true)->orderByDesc('published_at')->first()
            ?? $stories->first();

        return view('home', [
            'heroPhoto' => Photo::slot('HOME-01'),
            'lead' => $lead,
            'stories' => $stories,
            'destinations' => Destination::published()->with(['region', 'cardPhoto'])->orderBy('order')->take(6)->get(),
            'posts' => CommunityPost::published()->where('show_on_home', true)->with('photo')
                ->orderBy('order')->orderByDesc('posted_on')->take(2)->get(),
            'partners' => Partner::published()->with('category')->orderBy('order')->take(3)->get(),
        ]);
    }
}
