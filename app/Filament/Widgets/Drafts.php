<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Destinations\DestinationResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\Stories\StoryResource;
use App\Models\Destination;
use App\Models\Partner;
use App\Models\Story;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

/** Unpublished stories, destinations and partners, most recently edited first. */
class Drafts extends Widget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.drafts';

    public function getDrafts(): Collection
    {
        $map = fn (string $model, string $resource, string $type, string $title) => $model::where('is_published', false)
            ->latest('updated_at')->limit(10)->get()
            ->map(fn ($record) => [
                'type' => $type,
                'title' => $record->{$title},
                'edited' => $record->updated_at,
                'url' => $resource::getUrl('edit', ['record' => $record]),
            ]);

        return collect()
            ->concat($map(Story::class, StoryResource::class, 'Story', 'title'))
            ->concat($map(Destination::class, DestinationResource::class, 'Destination', 'name'))
            ->concat($map(Partner::class, PartnerResource::class, 'Certified partner', 'name'))
            ->sortByDesc('edited')
            ->take(12)
            ->values();
    }
}
