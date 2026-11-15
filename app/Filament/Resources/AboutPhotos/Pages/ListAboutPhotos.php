<?php

namespace App\Filament\Resources\AboutPhotos\Pages;

use App\Filament\Resources\AboutPhotos\AboutPhotoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAboutPhotos extends ListRecords
{
    protected static string $resource = AboutPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
