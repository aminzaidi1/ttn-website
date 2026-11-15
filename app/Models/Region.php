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

class Region extends Model
{
    use BustsPageCache, HasSlug;

    protected $guarded = ['id'];

    public function destinations(): HasMany { return $this->hasMany(Destination::class); }
    public function stories(): HasMany { return $this->hasMany(Story::class); }
    public function partners(): HasMany { return $this->hasMany(Partner::class); }
}
