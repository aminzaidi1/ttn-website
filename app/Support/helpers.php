<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /** Read an editable site text by key. Values are edited in the admin under Site text. */
    function setting(string $key, ?string $default = null): ?string
    {
        static $all = null;
        if ($all === null || app()->runningUnitTests()) {
            try {
                $all = Cache::rememberForever('ttn.settings', fn () => Setting::pluck('value', 'key')->all());
            } catch (Throwable) {
                $all = [];
            }
        }
        $value = $all[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }
}
