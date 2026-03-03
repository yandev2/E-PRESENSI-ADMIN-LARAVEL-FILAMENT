<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;

class ViewButtonComponen
{
    public static function make(): ViewAction
    {
        return ViewAction::make()
            ->color('info')
            ->button()
            ->icon(Heroicon::OutlinedEye)
            ->label('Lihat');
    }
}
