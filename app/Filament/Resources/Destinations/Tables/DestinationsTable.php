<?php

namespace App\Filament\Resources\Destinations\Tables;

use App\Filament\Support\Columns;
use App\Filament\Support\ViewOnSite;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DestinationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns::photo('cardPhoto'),
                TextColumn::make('name')->searchable()->sortable()
                    ->description(fn ($record) => $record->stat_season),
                TextColumn::make('region.name')->label('Region')->sortable(),
                Columns::published(),
                TextColumn::make('road_status_as_of')->label('Road status checked')->date('j M Y')->sortable()->placeholder('No status')->toggleable(),
                TextColumn::make('updated_at')->label('Last edited')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                TernaryFilter::make('is_published')->label('Status')->trueLabel('Published')->falseLabel('Drafts'),
                SelectFilter::make('region_id')->label('Region')->relationship('region', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                ViewOnSite::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
