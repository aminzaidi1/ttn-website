<?php

namespace App\Models\Concerns;

use App\Models\Redirect;
use Illuminate\Support\Str;

/**
 * Fills the slug from the name or title, keeps it unique, and records a
 * redirect when an editor changes it so old links keep working.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->slug ?: ($model->{$model->slugSource()} ?? '');
            $base = Str::slug($source) ?: 'item';
            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->whereKeyNot($model->getKey())->exists()) {
                $slug = $base.'-'.$i++;
            }
            $model->slug = $slug;
        });

        static::updated(function ($model) {
            if ($model->wasChanged('slug') && $model->pathPrefix() !== null) {
                $old = $model->pathPrefix().'/'.$model->getOriginal('slug');
                $new = $model->pathPrefix().'/'.$model->slug;
                Redirect::where('from_path', $new)->delete();
                Redirect::where('to_path', $old)->update(['to_path' => $new]);
                Redirect::updateOrCreate(['from_path' => $old], ['to_path' => $new]);
            }
        });
    }

    public function slugSource(): string
    {
        return 'name';
    }

    /** Public URL prefix, e.g. "/stories". Null means the model has no public page. */
    public function pathPrefix(): ?string
    {
        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getUrlAttribute(): ?string
    {
        return $this->pathPrefix() === null ? null : url($this->pathPrefix().'/'.$this->slug);
    }
}
