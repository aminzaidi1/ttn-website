<?php

namespace App\Filament\Resources\NavItems\Tables;

use App\Models\NavItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class NavItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('Link text')->searchable(),
                TextColumn::make('url')->label('Goes to')->searchable()->color('gray'),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->groups([
                Group::make('location')->label('Location')->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (NavItem $record): string => NavItem::LOCATIONS[$record->location] ?? $record->location),
            ])
            ->defaultGroup('location')
            ->groupingSettingsHidden()
            ->defaultSort('order')
            ->reorderable('order')
            ->paginated(false)
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
