<?php

namespace App\Models\Concerns;

use App\Support\PageCache;

/** Any change to content made in the admin clears the public page cache. */
trait BustsPageCache
{
    public static function bootBustsPageCache(): void
    {
        static::saved(fn () => PageCache::flush());
        static::deleted(fn () => PageCache::flush());
    }
}
