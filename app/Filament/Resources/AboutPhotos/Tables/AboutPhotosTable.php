<?php

namespace App\Filament\Resources\AboutPhotos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AboutPhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo.path')->label('Photo')->disk('public')->imageHeight(56),
                TextColumn::make('photo.caption')->label('Caption')->searchable()->wrap()
                    ->description(fn ($record) => $record->photo?->isPlaceholder() ? 'Placeholder: '.$record->photo->slot_code : null),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
