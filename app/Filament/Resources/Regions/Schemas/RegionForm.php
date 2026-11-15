<?php

namespace App\Filament\Resources\Regions\Schemas;

use App\Filament\Support\Copy;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('Regions drive the filters on the destinations, stories and partners pages.')
                ->schema([
                    Copy::text('name', 'Name')->required()->maxLength(255),
                    TextInput::make('slug')->label('Slug')->maxLength(255)
                        ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                        ->helperText('Used in filter links. Made from the name when left empty.'),
                    TextInput::make('order')->label('Position')->numeric()->default(0)
                        ->helperText('Lower numbers come first. You can also drag rows in the list.'),
                ]),
        ]);
    }
}
