<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use App\Filament\Support\Columns;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns::photo('photo', 'Portrait'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('role_line')->label('Role')->searchable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
