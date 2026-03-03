<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\AssociateAction;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class AssociateButtonComponen
{
    public static function make(): AssociateAction
    {
        return AssociateAction::make()
            ->authorize(true)
            ->color('danger')
            ->modalIcon(Heroicon::Plus)
            ->modalWidth(Width::FitContent)
            ->modalSubmitActionLabel('Tambah')
            ->modalAlignment(Alignment::Left)
            ->stickyModalHeader();
    }
}
