<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Models\Setting;
use App\Rules\CopyRules;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(fn (?Setting $record): string => $record?->label ?? 'Site text')
                ->description(fn (?Setting $record): ?string => $record ? 'Section: '.$record->group : null)
                ->columnSpanFull()
                ->schema([
                    Textarea::make('value')
                        ->label('Text')
                        ->rows(5)->autosize()
                        ->rule(new CopyRules)
                        ->helperText(fn (?Setting $record): ?string => $record?->help)
                        ->visible(fn (?Setting $record): bool => $record?->type === 'textarea'),
                    TextInput::make('value')
                        ->label('Text')
                        ->rule(new CopyRules)
                        ->helperText(fn (?Setting $record): ?string => $record?->help)
                        ->visible(fn (?Setting $record): bool => $record?->type !== 'textarea'),
                ]),
        ]);
    }
}
