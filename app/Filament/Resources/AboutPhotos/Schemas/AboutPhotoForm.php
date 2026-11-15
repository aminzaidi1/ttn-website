<?php

namespace App\Filament\Resources\AboutPhotos\Schemas;

use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutPhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('The photos shown on the About page, in order.')
                ->schema([
                    PhotoPicker::make('photo_id', 'Photo')->required(),
                    TextInput::make('order')->label('Position')->numeric()->default(0)
                        ->helperText('Lower numbers come first. You can also drag rows in the list.'),
                ]),
        ]);
    }
}
