<?php

namespace App\Filament\Resources\Authors\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('Authors sign stories and appear on the author card at the end of each one. They do not log in.')
                ->schema([
                    Copy::text('name', 'Name')->required()->maxLength(255),
                    Copy::text('role_line', 'Role')->maxLength(255)->helperText('e.g. Contributor, Skardu'),
                    Copy::area('bio', 'Short bio', 3)->columnSpanFull(),
                    PhotoPicker::make('photo_id', 'Portrait'),
                    TextInput::make('slug')->label('Slug')->maxLength(255)
                        ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                        ->helperText('Made from the name when left empty.'),
                ]),
        ]);
    }
}
