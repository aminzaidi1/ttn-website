<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public const PER_PAGE = 12;

    public function index(Request $request)
    {
        $regions = Region::orderBy('order')->get();
        $region = null;
        if ($request->filled('region')) {
            $region = $regions->firstWhere('slug', (string) $request->query('region'));
            abort_unless($region, 404);
        }

        $base = Story::published()
            ->when($region, fn ($q) => $q->where('region_id', $region->id));

        // The lead: the story marked as featured when it is in this view, otherwise the latest.
        $featured = (clone $base)->with(['category', 'author', 'cardPhoto', 'heroPhoto'])
            ->orderByDesc('is_featured')->orderByDesc('published_at')->first();

        $rest = (clone $base)->with(['category', 'author', 'cardPhoto'])
            ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
            ->orderByDesc('published_at')
            ->simplePaginate(self::PER_PAGE)
            ->withQueryString();
        abort_if(! $rest->onFirstPage() && $rest->isEmpty(), 404);

        return view('stories.index', [
            'regions' => $regions,
            'region' => $region,
            'featured' => $rest->onFirstPage() ? $featured : null,
            'hasStories' => (bool) $featured,
            'rest' => $rest,
        ]);
    }

    public function show(Story $story)
    {
        // Drafts and scheduled stories are not public.
        abort_unless(Story::published()->whereKey($story->getKey())->exists(), 404);

        $story->load(['author.photo', 'category', 'region', 'heroPhoto', 'cardPhoto']);

        // The author card links on to the author's next story, as in the kit.
        $next = $story->author_id
            ? Story::published()->where('author_id', $story->author_id)->whereKeyNot($story->id)
                ->orderByDesc('published_at')->first()
            : null;

        return view('stories.show', ['story' => $story, 'next' => $next]);
    }
}
