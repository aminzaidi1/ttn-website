<?php

namespace App\Models;

use App\Models\Concerns\BustsPageCache;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavItem extends Model
{
    use BustsPageCache;

    protected $guarded = ['id'];

    protected $casts = ["is_visible" => "boolean"];

    public const LOCATIONS = ["header" => "Top navigation", "footer_explore" => "Footer: Explore column", "footer_community" => "Footer: Community column"];

    /** All visible links for one location, cached with the page cache. */
    public static function for(string $location)
    {
        $all = \Illuminate\Support\Facades\Cache::rememberForever("ttn.nav", fn () => static::where("is_visible", true)->orderBy("order")->get(["location", "label", "url"])->toArray());

        return collect($all)->where("location", $location)->values();
    }
}
