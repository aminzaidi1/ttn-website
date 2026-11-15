<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Partner;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /** The /search page: works without JavaScript and is where the overlay form submits. */
    public function index(Request $request)
    {
        $query = $this->clean($request);

        return view('search', ['query' => $query, 'results' => $this->find($query)]);
    }

    /** HTML fragment for the live overlay. */
    public function results(Request $request)
    {
        $query = $this->clean($request);

        return view('components.ttn.search-results', ['query' => $query, 'results' => $this->find($query)]);
    }

    protected function clean(Request $request): string
    {
        return Str::limit(trim((string) $request->query('q', '')), 80, '');
    }

    protected function find(string $query): array
    {
        if (mb_strlen($query) < 2) {
            return ['stories' => [], 'destinations' => [], 'partners' => []];
        }
        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query).'%';
        $match = fn ($q, array $cols) => $q->where(function ($w) use ($cols, $like) {
            foreach ($cols as $col) {
                $w->orWhere($col, 'like', $like);
            }
        });

        return [
            'stories' => $match(Story::published()->with('category'), ['title', 'excerpt', 'location', 'lead'])
                ->latest('published_at')->limit(8)->get()
                ->map(fn ($s) => ['title' => $s->title, 'url' => $s->url, 'meta' => collect([$s->category?->name, $s->read_time])->filter()->implode(' · ')])->all(),
            'destinations' => $match(Destination::published()->with('region'), ['name', 'blurb'])
                ->orderBy('order')->limit(8)->get()
                ->map(fn ($d) => ['title' => $d->name, 'url' => $d->url, 'meta' => $d->region?->name])->all(),
            'partners' => $match(Partner::published()->with('category'), ['name', 'location', 'description'])
                ->orderBy('order')->limit(8)->get()
                ->map(fn ($p) => ['title' => $p->name, 'url' => $p->url, 'meta' => collect([$p->category?->name, $p->location])->filter()->implode(' · ')])->all(),
        ];
    }
}
