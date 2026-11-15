<?php

namespace App\Filament\Support;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Database\Eloquent\Model;

/** Initials on a dark disc, drawn locally so the admin never calls an outside avatar service. */
class InitialsAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        $name = (string) filament()->getUserName($record);
        $initials = collect(preg_split('/\s+/', trim($name)))->filter()->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">'
            .'<rect width="64" height="64" fill="#1f1f1f"/>'
            .'<text x="50%" y="50%" dy=".35em" text-anchor="middle" font-family="Arial, sans-serif" font-size="26" font-weight="700" fill="#F5A623">'
            .e($initials ?: '?').'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
