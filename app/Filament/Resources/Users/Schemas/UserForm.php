<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)
                ->description('People who can log in to this admin.')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                    Select::make('role')->label('Role')->required()->native(false)->default('editor')
                        ->options(['editor' => 'Editor: manages content', 'admin' => 'Admin: also manages setup and users']),
                    TextInput::make('password')->label('Password')
                        ->password()->revealable()
                        ->minLength(10)
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText(fn (string $operation): string => $operation === 'create'
                            ? 'At least 10 characters. Share it with them privately.'
                            : 'Leave empty to keep the current password.'),
                ]),
        ]);
    }
}
