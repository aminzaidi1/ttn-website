<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Full-page cache for anonymous visitors. Pages are keyed by a version
 * number; saving anything in the admin bumps the version, so every cached
 * page is replaced on its next visit without anyone clearing anything by hand.
 */
class PageCache
{
    public static function version(): int
    {
        return (int) Cache::rememberForever('ttn.page_version', fn () => 1);
    }

    public static function flush(): void
    {
        Cache::forever('ttn.page_version', static::version() + 1);
        Cache::forget('ttn.settings');
        Cache::forget('ttn.nav');
    }

    public static function key(string $uri): string
    {
        return 'ttn.page.'.static::version().'.'.sha1($uri);
    }
}
