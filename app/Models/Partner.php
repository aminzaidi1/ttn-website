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

class Partner extends Model
{
    use BustsPageCache, HasSlug, Publishable;

    protected $guarded = ['id'];

    protected $casts = ["certified_since" => "date", "published_at" => "datetime", "is_published" => "boolean"];

    public function pathPrefix(): ?string { return "/certified"; }
    public function category(): BelongsTo { return $this->belongsTo(PartnerCategory::class, "partner_category_id"); }
    public function region(): BelongsTo { return $this->belongsTo(Region::class); }
    public function heroPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "hero_photo_id"); }
    public function thumbPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "thumb_photo_id"); }
    public function offers(): HasMany { return $this->hasMany(PartnerOffer::class)->orderBy("order"); }
    public function galleryPhotos(): BelongsToMany { return $this->belongsToMany(Photo::class)->withPivot("order")->withTimestamps()->orderByPivot("order"); }
    public function destinations(): BelongsToMany { return $this->belongsToMany(Destination::class)->withPivot("order")->withTimestamps(); }

    public function getSinceLabelAttribute(): ?string { return $this->certified_since?->format("F Y"); }
}
