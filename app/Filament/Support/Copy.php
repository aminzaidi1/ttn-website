<?php

namespace App\Filament\Support;

use App\Rules\CopyRules;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * Text fields editors type copy into. Each one carries the copy rules
 * (no em dashes, no emoji), so the rules hold without a developer.
 */
class Copy
{
    /** Bold, italic and links only. */
    public const INLINE = [['bold', 'italic', 'link'], ['undo', 'redo']];

    /** Headings, quotes and lists as well. */
    public const STRUCTURED = [['bold', 'italic', 'link'], ['h2', 'h3'], ['blockquote', 'bulletList', 'orderedList'], ['undo', 'redo']];

    /** Story body: the structured set plus images. */
    public const STORY = [['bold', 'italic', 'link'], ['h2', 'h3'], ['blockquote', 'bulletList', 'orderedList'], ['attachFiles'], ['undo', 'redo']];

    public static function text(string $name, ?string $label = null): TextInput
    {
        return TextInput::make($name)->label($label)->rule(new CopyRules);
    }

    public static function area(string $name, ?string $label = null, int $rows = 3): Textarea
    {
        return Textarea::make($name)->label($label)->rows($rows)->autosize()->rule(new CopyRules);
    }

    public static function rich(string $name, ?string $label = null, array $toolbar = self::INLINE): RichEditor
    {
        return RichEditor::make($name)->label($label)->toolbarButtons($toolbar)->rule(new CopyRules);
    }

    public static function storyBody(string $name = 'body', ?string $label = 'Story'): RichEditor
    {
        return static::rich($name, $label, self::STORY)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('stories')
            ->fileAttachmentsVisibility('public');
    }
}
