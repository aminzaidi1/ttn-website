<?php

namespace App\Filament\Resources\AboutPhotos\Pages;

use App\Filament\Resources\AboutPhotos\AboutPhotoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAboutPhoto extends EditRecord
{
    protected static string $resource = AboutPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
