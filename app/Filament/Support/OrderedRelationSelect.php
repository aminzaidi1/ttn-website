<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

/**
 * A multi-select over a belongs-to-many relation whose pivot has an `order`
 * column. The selected chips can be dragged; the order is saved to the pivot.
 */
class OrderedRelationSelect
{
    public static function make(string $relationship, string $label, string $titleAttribute = 'name'): Select
    {
        return Select::make($relationship)
            ->label($label)
            ->multiple()
            ->reorderable()
            ->relationship($relationship, $titleAttribute)
            ->preload()
            ->searchable()
            ->saveRelationshipsUsing(function (Select $component, Model $record) use ($relationship): void {
                $ids = collect($component->getState() ?? [])->values();
                $record->{$relationship}()->sync(
                    $ids->mapWithKeys(fn ($id, int $i): array => [$id => ['order' => $i]])->all()
                );
                $record->unsetRelation($relationship);
            });
    }
}
