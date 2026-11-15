<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('The people shown on the About page.')
                ->schema([
                    Copy::text('name', 'Name')->required()->maxLength(255),
                    Copy::text('role_line', 'Role')->maxLength(255)->helperText('e.g. Founder, Gilgit'),
                    PhotoPicker::make('photo_id', 'Portrait'),
                    TextInput::make('order')->label('Position')->numeric()->default(0)
                        ->helperText('Lower numbers come first. You can also drag rows in the list.'),
                ]),
        ]);
    }
}
