<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('What it is')->searchable()->weight('medium')
                    ->description(fn ($record) => $record->help),
                TextColumn::make('value')->label('Current text')->searchable()->limit(90)->wrap()->placeholder('Empty'),
            ])
            ->groups([
                Group::make('group')->label('Section')->titlePrefixedWithLabel(false)->collapsible(),
            ])
            ->defaultGroup('group')
            ->groupingSettingsHidden()
            ->defaultSort('order')
            ->paginated([25, 50, 100, 'all'])
            ->defaultPaginationPageOption(100)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
