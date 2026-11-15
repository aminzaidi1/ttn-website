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

class CommunityPost extends Model
{
    use BustsPageCache;

    protected $guarded = ['id'];

    protected $casts = ["posted_on" => "date", "is_published" => "boolean", "show_on_home" => "boolean", "permission_confirmed" => "boolean"];

    public function photo(): BelongsTo { return $this->belongsTo(Photo::class); }
    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }

    /** A post is public only when the member has agreed to be featured. */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where("is_published", true)->where("permission_confirmed", true);
    }

    public function getDateLabelAttribute(): ?string { return $this->posted_on?->format("j F Y"); }
}
