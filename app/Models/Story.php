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

class Story extends Model
{
    use BustsPageCache, HasSlug, Publishable;

    protected $guarded = ['id'];

    protected $casts = ["published_at" => "datetime", "is_published" => "boolean", "is_featured" => "boolean"];

    protected static function booted(): void
    {
        static::saving(function (Story $story) {
            if (! $story->read_minutes || $story->isDirty("body")) {
                $words = str_word_count(strip_tags(($story->lead ?? "")." ".($story->body ?? "")));
                $story->read_minutes = max(1, (int) round($words / 220));
            }
        });
        // Only one story leads the Stories page at a time.
        static::saved(function (Story $story) {
            if ($story->is_featured) {
                static::whereKeyNot($story->getKey())->where("is_featured", true)->get()
                    ->each(fn (Story $other) => $other->forceFill(["is_featured" => false])->saveQuietly());
            }
        });
    }

    public function slugSource(): string { return "title"; }
    public function pathPrefix(): ?string { return "/stories"; }
    public function author(): BelongsTo { return $this->belongsTo(Author::class); }
    public function category(): BelongsTo { return $this->belongsTo(StoryCategory::class, "story_category_id"); }
    public function region(): BelongsTo { return $this->belongsTo(Region::class); }
    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
    public function heroPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "hero_photo_id"); }
    public function cardPhoto(): BelongsTo { return $this->belongsTo(Photo::class, "card_photo_id"); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where("is_published", true)->where("published_at", "<=", now());
    }

    public function getDateLabelAttribute(): ?string { return $this->published_at?->format("j F Y"); }
    public function getReadTimeAttribute(): ?string { return $this->read_minutes ? $this->read_minutes." min" : null; }
}
