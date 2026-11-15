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

class Destination extends Model
{
    use BustsPageCache, HasSlug, Publishable;

    protected $guarded = ['id'];

    protected $casts = ["road_status_as_of" => "date", "published_at" => "datetime", "is_published" => "boolean",
        "lat" => "float", "lng" => "float", "origin_lat" => "float", "origin_lng" => "float"];

    public function pathPrefix(): ?string { return "/destinations"; }
    public function region(): BelongsTo { return $this->belongsTo(Region::class); }
    public function heroPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "hero_photo_id"); }
    public function cardPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "card_photo_id"); }
    public function blocks(): HasMany { return $this->hasMany(ContentBlock::class)->where("type", "section")->orderBy("order"); }
    public function routeLegs(): HasMany { return $this->hasMany(ContentBlock::class)->where("type", "route_leg")->orderBy("order"); }
    public function stripPhotos(): BelongsToMany { return $this->belongsToMany(Photo::class)->withPivot("order")->withTimestamps()->orderByPivot("order"); }
    public function partners(): BelongsToMany { return $this->belongsToMany(Partner::class)->withPivot("order")->withTimestamps()->orderByPivot("order"); }
    public function communityPosts(): HasMany { return $this->hasMany(CommunityPost::class); }
    public function stories(): HasMany { return $this->hasMany(Story::class); }

    /** Road status shows only when it has both text and a date. */
    public function hasRoadStatus(): bool { return filled($this->road_status_text) && $this->road_status_as_of !== null; }

    /** The five at-a-glance stats, skipping any left empty. */
    public function stats(): array
    {
        return array_filter([
            ["icon" => "calendar", "label" => "Best season", "value" => $this->stat_season],
            ["icon" => "mountain", "label" => "Altitude", "value" => $this->stat_altitude],
            ["icon" => "car", "label" => "Drive from Islamabad", "value" => $this->stat_drive],
            ["icon" => "gauge", "label" => "Road difficulty", "value" => $this->stat_difficulty],
            ["icon" => "fileCheck", "label" => "Permits", "value" => $this->stat_permits],
        ], fn ($s) => filled($s["value"]));
    }
}
