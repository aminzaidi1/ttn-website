<?php

namespace App\Filament\Resources\PartnerCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartnerCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('plural_name')->label('Filter label'),
                TextColumn::make('slug')->color('gray')->toggleable(),
                TextColumn::make('partners_count')->label('Partners')->counts('partners'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->paginated(false)
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
