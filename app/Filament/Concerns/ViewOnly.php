<?php

namespace App\Filament\Concerns;

trait ViewOnly
{
    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function canRestore($record): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->user()->hasRole('master');
    }
}
