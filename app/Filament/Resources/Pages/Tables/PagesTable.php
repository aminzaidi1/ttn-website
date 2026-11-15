<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Filament\Support\ViewOnSite;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->label('Web address')->formatStateUsing(fn ($state) => '/'.$state)->color('gray')->searchable(),
                TextColumn::make('updated_at')->label('Last edited')->since()->sortable(),
            ])
            ->defaultSort('title')
            ->recordActions([EditAction::make(), ViewOnSite::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
