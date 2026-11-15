<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public function getSubheading(): ?string
    {
        return 'Every heading, line and link on the site that is not part of a story, destination or partner. Click a row to change it.';
    }
}
