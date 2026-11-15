<?php

namespace App\Filament\Resources\Stories\Tables;

use App\Filament\Support\Columns;
use App\Filament\Support\ViewOnSite;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(60)->wrap()
                    ->description(fn ($record) => $record->location),
                TextColumn::make('category.name')->label('Category')->sortable()->toggleable(),
                TextColumn::make('author.name')->label('Author')->sortable()->toggleable(),
                Columns::published(),
                IconColumn::make('is_featured')->label('Featured')->boolean()
                    ->trueIcon('heroicon-s-star')->falseIcon('heroicon-o-minus')->trueColor('primary')->falseColor('gray'),
                TextColumn::make('published_at')->label('Publish date')->date('j M Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                TernaryFilter::make('is_published')->label('Status')
                    ->trueLabel('Published')->falseLabel('Drafts'),
                SelectFilter::make('story_category_id')->label('Category')->relationship('category', 'name'),
                SelectFilter::make('author_id')->label('Author')->relationship('author', 'name'),
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
