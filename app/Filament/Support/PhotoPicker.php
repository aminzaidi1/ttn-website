<?php

namespace App\Filament\Support;

use App\Models\Photo;
use App\Rules\CopyRules;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * One way to choose a photo everywhere: search the library by slot code or
 * caption, or upload a new one without leaving the page.
 */
class PhotoPicker
{
    /** A single photo, e.g. PhotoPicker::make('hero_photo_id', 'Hero photo'). */
    public static function make(string $field, string $label, ?string $relationship = null): Select
    {
        $relationship ??= Str::camel(Str::beforeLast($field, '_id'));

        return Select::make($field)
            ->label($label)
            ->relationship($relationship, 'caption')
            ->getOptionLabelFromRecordUsing(fn (Photo $record): string => static::label($record))
            ->searchable(['caption', 'slot_code'])
            ->preload()
            ->placeholder('Search by slot code or caption')
            ->createOptionForm(static::uploadForm())
            ->createOptionModalHeading('Add a photo to the library');
    }

    /**
     * An ordered set of photos through a pivot table with an `order` column,
     * e.g. a destination's photo strip. Editors add, remove and drag to reorder.
     */
    public static function ordered(string $relationship, string $label): Select
    {
        return OrderedRelationSelect::make($relationship, $label, 'caption')
            ->getOptionLabelFromRecordUsing(fn (Photo $record): string => static::label($record))
            ->searchable(['caption', 'slot_code'])
            ->createOptionForm(static::uploadForm())
            ->createOptionModalHeading('Add a photo to the library')
            ->createOptionUsing(fn (array $data): int => Photo::create($data)->getKey());
    }

    public static function label(Photo $photo): string
    {
        return $photo->label.($photo->isPlaceholder() ? ' (placeholder)' : '');
    }

    /** @return array<\Filament\Schemas\Components\Component> */
    public static function uploadForm(): array
    {
        return [
            FileUpload::make('path')
                ->label('Photograph')
                ->image()
                ->disk('public')
                ->directory('photos')
                ->visibility('public')
                ->maxSize(20 * 1024)
                ->helperText('Leave empty to keep this as a labelled placeholder slot until the real photograph arrives.'),
            TextInput::make('caption')
                ->required()
                ->rule(new CopyRules)
                ->helperText('Always names the location, e.g. Kharfaq Lake, Gilgit-Baltistan'),
            TextInput::make('alt')
                ->label('Description for screen readers')
                ->rule(new CopyRules)
                ->helperText('What the photo shows, in one plain sentence. Leave empty to use the caption.'),
        ];
    }
}
