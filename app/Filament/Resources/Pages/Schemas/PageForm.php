<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\Copy;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('Simple pages such as privacy, terms and contact.')
                ->schema([
                    Copy::text('title', 'Title')->required()->maxLength(255),
                    TextInput::make('slug')->label('Web address')->prefix('/')->maxLength(255)
                        ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                        ->helperText('Made from the title when left empty. Changing it keeps old links working.'),
                    Copy::rich('body', 'Text', \App\Filament\Support\Copy::STRUCTURED)->columnSpanFull(),
                    Copy::area('seo_description', 'Search description', 2)->maxLength(320)->columnSpanFull()
                        ->helperText('How the page is described on Google.'),
                ]),
        ]);
    }
}
