<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;

class CommunityController extends Controller
{
    /** Community: published member posts (destination tips live on their destinations), newest first. */
    public function __invoke()
    {
        $posts = CommunityPost::published()
            ->whereNull('destination_id')
            ->with('photo')
            ->orderByDesc('posted_on')
            ->orderBy('order')
            ->get();

        return view('community', compact('posts'));
    }
}
