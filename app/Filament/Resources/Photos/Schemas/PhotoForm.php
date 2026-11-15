<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Filament\Support\Copy;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('The photograph')->columnSpanFull()->schema([
                FileUpload::make('path')
                    ->label('Photograph')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('photos')
                    ->visibility('public')
                    ->maxSize(20 * 1024)
                    ->helperText('Leave empty to keep this as a labelled placeholder slot until the real photograph arrives. Smaller sizes for the website are made automatically.'),
                Copy::text('caption', 'Caption')->required()->maxLength(255)
                    ->helperText('Always names the location, e.g. Kharfaq Lake, Gilgit-Baltistan'),
                Copy::text('alt', 'Description for screen readers')->maxLength(255)
                    ->helperText('What the photo shows, in one plain sentence. Leave empty to use the caption.'),
                Copy::text('credit', 'Credit')->maxLength(255)->default('Photo: Waseem Photography'),
            ]),
            Section::make('Photography brief')
                ->description('Used while a photo is still a placeholder slot.')
                ->columnSpanFull()->columns(2)->collapsible()
                ->schema([
                    TextInput::make('slot_code')->label('Slot code')->maxLength(60)->placeholder('e.g. HOME-01')
                        ->helperText('The frame number from the photography brief.'),
                    Copy::text('slot_note', 'What the slot is waiting for')->maxLength(255),
                ]),
        ]);
    }
}
