<?php

namespace App\Filament\Support;

/** Sentence case per the copy rules: "Community posts" as a page title, "Create community post" as a heading. */
trait SentenceCaseLabels
{
    public static function getTitleCaseModelLabel(): string
    {
        return static::getModelLabel();
    }

    public static function getTitleCasePluralModelLabel(): string
    {
        return ucfirst(static::getPluralModelLabel());
    }
}
