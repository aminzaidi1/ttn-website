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
                    ->getTitleFromRecordUsing(fn (NavItem $record): string => NavItem::LOCATIONS[$record->location] ?? $record->location)
                    ->orderQueryUsing(fn ($query, string $direction) => $query->orderByRaw(
                        "case location when 'header' then 0 when 'footer_explore' then 1 when 'footer_community' then 2 else 3 end"
                    )),
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
