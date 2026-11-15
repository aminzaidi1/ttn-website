<?php

namespace App\Filament\Support;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class Columns
{
    /** A Published / Draft badge for an `is_published` column. */
    public static function published(string $name = 'is_published'): TextColumn
    {
        return TextColumn::make($name)
            ->label('Status')
            ->badge()
            ->formatStateUsing(fn ($state): string => $state ? 'Published' : 'Draft')
            ->color(fn ($state): string => $state ? 'success' : 'gray')
            ->sortable();
    }

    /** A small thumbnail of a related photo, e.g. Columns::photo('heroPhoto'). */
    public static function photo(string $relationship, string $label = 'Photo'): ImageColumn
    {
        return ImageColumn::make($relationship.'.path')
            ->label($label)
            ->disk('public')
            ->imageHeight(40)
            ->square()
            ->toggleable();
    }
}
