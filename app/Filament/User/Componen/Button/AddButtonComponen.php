<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class AddButtonComponen
{
    public static function make(): CreateAction
    {
        return CreateAction::make()
            ->color('info')
            ->icon(Heroicon::PlusCircle)
            ->label('Tambah Data');
    }
}
