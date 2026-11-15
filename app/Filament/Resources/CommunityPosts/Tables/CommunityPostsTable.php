<?php

namespace App\Filament\Resources\CommunityPosts\Tables;

use App\Filament\Support\Columns;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommunityPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_name')->label('Member')->searchable()->sortable(),
                TextColumn::make('quote')->limit(80)->wrap()->searchable(),
                TextColumn::make('permission_confirmed')->label('Permission')->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Confirmed' : 'Missing permission')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->icon(fn ($state) => $state ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationTriangle)
                    ->sortable(),
                Columns::published(),
                IconColumn::make('show_on_home')->label('Homepage')->boolean()->toggleable(),
                TextColumn::make('destination.name')->label('Destination')->placeholder('None')->toggleable(),
                TextColumn::make('posted_on')->label('Posted on')->date('j M Y')->sortable(),
            ])
            ->defaultSort('posted_on', 'desc')
            ->filters([
                TernaryFilter::make('permission_confirmed')->label('Permission')
                    ->trueLabel('Confirmed')->falseLabel('Missing permission'),
                TernaryFilter::make('is_published')->label('Status')->trueLabel('Published')->falseLabel('Drafts'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('facebook')->label('Open on Facebook')->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')->url(fn ($record) => $record->facebook_url)->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
