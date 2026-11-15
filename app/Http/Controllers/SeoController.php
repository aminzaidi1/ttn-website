<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Story;

class SeoController extends Controller
{
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /styleguide',
            'Disallow: /search',
            'Disallow: /newsletter',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap()
    {
        $urls = collect([
            ['loc' => url('/'), 'lastmod' => null, 'priority' => '1.0'],
            ['loc' => url('/destinations'), 'lastmod' => Destination::published()->max('updated_at'), 'priority' => '0.9'],
            ['loc' => url('/stories'), 'lastmod' => Story::published()->max('updated_at'), 'priority' => '0.9'],
            ['loc' => url('/certified'), 'lastmod' => Partner::published()->max('updated_at'), 'priority' => '0.8'],
            ['loc' => url('/community'), 'lastmod' => null, 'priority' => '0.6'],
            ['loc' => url('/about'), 'lastmod' => null, 'priority' => '0.5'],
        ]);

        $add = fn ($models, $priority) => $models->each(fn ($m) => $urls->push(['loc' => $m->url, 'lastmod' => $m->updated_at, 'priority' => $priority]));
        $add(Destination::published()->get(['slug', 'updated_at']), '0.8');
        $add(Story::published()->get(['slug', 'updated_at']), '0.7');
        $add(Partner::published()->get(['slug', 'updated_at']), '0.6');
        $add(Page::get(['slug', 'updated_at']), '0.3');

        return response()->view('seo.sitemap', ['urls' => $urls], 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function feed()
    {
        $stories = Story::published()->with('author')->latest('published_at')->limit(30)->get();

        return response()->view('seo.feed', ['stories' => $stories], 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }
}
