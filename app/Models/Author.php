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

class Author extends Model
{
    use BustsPageCache, HasSlug;

    protected $guarded = ['id'];

    public function photo(): BelongsTo { return $this->belongsTo(Photo::class); }
    public function stories(): HasMany { return $this->hasMany(Story::class); }
}
