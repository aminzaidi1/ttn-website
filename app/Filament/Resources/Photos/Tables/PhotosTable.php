<?php

namespace App\Filament\Resources\Photos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')->label('Photo')->disk('public')->imageHeight(56),
                TextColumn::make('placeholder')->label('')->badge()->color('warning')
                    ->state(fn ($record): ?string => $record->isPlaceholder() ? 'Placeholder' : null),
                TextColumn::make('caption')->searchable()->sortable()->wrap()
                    ->description(fn ($record) => $record->isPlaceholder() ? $record->slot_note : $record->credit),
                TextColumn::make('slot_code')->label('Slot')->searchable()->sortable()->placeholder('None'),
                TextColumn::make('width')->label('Size')->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state, $record) => $state ? $state.' x '.$record->height : null),
                TextColumn::make('updated_at')->label('Last edited')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('slot_code')
            ->filters([
                Filter::make('placeholders')->label('Placeholders only')->toggle()
                    ->query(fn (Builder $query) => $query->whereNull('path')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
