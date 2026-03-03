<?php

namespace App\Filament\User\Componen\Button;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;

class EditButtonComponen
{
    public static function make(): EditAction
    {
        return EditAction::make()
            ->color('warning')
            ->button()
            ->authorize(true)
            ->icon(Heroicon::PencilSquare)
            ->label('Edit');
    }
}
