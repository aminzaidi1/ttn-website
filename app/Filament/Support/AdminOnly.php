<?php

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Model;

/** For resources in the Setup group: admins only, editors never see them. */
trait AdminOnly
{
    protected static function userIsAdmin(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function canAccess(): bool { return static::userIsAdmin(); }
    public static function canViewAny(): bool { return static::userIsAdmin(); }
    public static function canCreate(): bool { return static::userIsAdmin(); }
    public static function canEdit(Model $record): bool { return static::userIsAdmin(); }
    public static function canDelete(Model $record): bool { return static::userIsAdmin(); }
    public static function canDeleteAny(): bool { return static::userIsAdmin(); }
}
