<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class ViewOnSite
{
    public static function make(): Action
    {
        return Action::make('viewOnSite')
            ->label('View on site')
            ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
            ->color('gray')
            ->url(fn (?Model $record): ?string => $record?->url)
            ->openUrlInNewTab()
            ->visible(fn (?Model $record): bool => filled($record?->exists ? $record->url : null));
    }
}
