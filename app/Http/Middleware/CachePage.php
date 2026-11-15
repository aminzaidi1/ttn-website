<?php

namespace App\Http\Middleware;

use App\Support\PageCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves public pages from the cache. A cached page costs no database
 * queries. The cache empties itself whenever content is saved in the admin.
 */
class CachePage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('ttn.page_cache') || ! $request->isMethod('GET') || $request->ajax()) {
            return $next($request);
        }

        $key = PageCache::key($request->fullUrl());

        if ($cached = Cache::get($key)) {
            return response($cached['body'], 200, $cached['headers'] + ['X-TTN-Cache' => 'hit']);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 200 && is_string($response->getContent())) {
            Cache::put($key, [
                'body' => $response->getContent(),
                'headers' => ['Content-Type' => $response->headers->get('Content-Type', 'text/html; charset=UTF-8')],
            ], now()->addHours(12));
            $response->headers->set('X-TTN-Cache', 'miss');
        }

        return $response;
    }
}
