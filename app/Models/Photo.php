<?php

namespace App\Models;

use App\Models\Concerns\BustsPageCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * One image in the library. A photo with no file is still useful: it renders
 * as a labelled placeholder slot (slot code + note from the photography brief)
 * until the real frame is uploaded.
 */
class Photo extends Model
{
    use BustsPageCache;

    public const WIDTHS = [480, 800, 1280, 1920];

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (Photo $photo) {
            if ($photo->isDirty('path')) {
                $photo->path ? $photo->buildVariants() : $photo->forceFill(['width' => null, 'height' => null]);
            }
        });
        static::deleted(fn (Photo $photo) => $photo->deleteFiles());
    }

    /** Find a photo by its brief slot code, e.g. Photo::slot('HOME-01'). */
    public static function slot(string $code): ?self
    {
        return static::where('slot_code', $code)->first();
    }

    public function isPlaceholder(): bool
    {
        return blank($this->path);
    }

    public function getLabelAttribute(): string
    {
        return trim(($this->slot_code ? $this->slot_code.' · ' : '').$this->caption);
    }

    public function getAltTextAttribute(): string
    {
        return $this->alt ?: $this->caption;
    }

    public function url(?int $width = null): ?string
    {
        if ($this->isPlaceholder()) {
            return null;
        }
        if ($width && $this->width) {
            $w = collect(self::WIDTHS)->first(fn ($w) => $w >= $width && $w <= $this->width);
            if ($w) {
                return Storage::disk('public')->url($this->variantPath($w));
            }
        }

        return Storage::disk('public')->url($this->path);
    }

    public function srcset(): ?string
    {
        if ($this->isPlaceholder() || ! $this->width) {
            return null;
        }
        $parts = collect(self::WIDTHS)->filter(fn ($w) => $w <= $this->width)
            ->map(fn ($w) => Storage::disk('public')->url($this->variantPath($w)).' '.$w.'w');

        return $parts->isEmpty() ? null : $parts->implode(', ');
    }

    protected function variantPath(int $w): string
    {
        return 'photos/variants/'.pathinfo($this->path, PATHINFO_FILENAME).'-'.$w.'.webp';
    }

    /** Resize once at upload time so no visitor ever waits for it. */
    public function buildVariants(): void
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($this->path)) {
            return;
        }
        try {
            $manager = new ImageManager(new Driver);
            $image = $manager->read($disk->path($this->path));
            $this->width = $image->width();
            $this->height = $image->height();
            $disk->makeDirectory('photos/variants');
            foreach (self::WIDTHS as $w) {
                if ($w <= $this->width) {
                    $manager->read($disk->path($this->path))->scaleDown(width: $w)
                        ->toWebp(78)->save($disk->path($this->variantPath($w)));
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    protected function deleteFiles(): void
    {
        if ($this->path) {
            $disk = Storage::disk('public');
            foreach (self::WIDTHS as $w) {
                $disk->delete($this->variantPath($w));
            }
            $disk->delete($this->path);
        }
    }
}
