<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public static function bootPublishable(): void
    {
        static::saving(function ($model) {
            if ($model->is_published && $model->isFillable('published_at') && ! $model->published_at) {
                $model->published_at = now();
            }
        });
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where($this->getTable().'.is_published', true);
    }
}
