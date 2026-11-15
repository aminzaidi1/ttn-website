<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('Redirects are created automatically when a slug changes, so old links and Google results keep working. Add one by hand only to send an old address somewhere new.')
                ->schema([
                    TextInput::make('from_path')->label('Old address')->required()->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->regex('#^/#')
                        ->placeholder('/stories/old-name')
                        ->helperText('A path on this site, starting with /'),
                    TextInput::make('to_path')->label('Send visitors to')->required()->maxLength(255)
                        ->regex('#^(/|https?://)#')
                        ->placeholder('/stories/new-name')
                        ->helperText('A path on this site like /stories, or a full https:// link'),
                ]),
        ]);
    }
}
