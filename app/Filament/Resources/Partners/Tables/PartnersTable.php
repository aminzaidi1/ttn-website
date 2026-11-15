<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Filament\Support\Columns;
use App\Filament\Support\ViewOnSite;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns::photo('thumbPhoto'),
                TextColumn::make('name')->searchable()->sortable()->description(fn ($record) => $record->location),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('region.name')->label('Region')->sortable()->toggleable(),
                TextColumn::make('certified_since')->label('Certified since')->date('F Y')->sortable()->placeholder('Not yet'),
                Columns::published(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                TernaryFilter::make('is_published')->label('Status')->trueLabel('Published')->falseLabel('Drafts'),
                SelectFilter::make('partner_category_id')->label('Category')->relationship('category', 'name'),
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
