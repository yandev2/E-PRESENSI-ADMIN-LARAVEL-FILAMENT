<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\Action;
use Filament\Actions\ImportAction;
use Filament\Support\Enums\Alignment;

class ImportButtonComponen
{
    public static function make(): ImportAction
    {
        return ImportAction::make()
            ->modalWidth('md')
            ->color('warning')
            ->modalIcon('heroicon-o-arrow-down-on-square-stack')
            ->icon('heroicon-o-arrow-down-on-square-stack')
            ->modalAlignment(Alignment::Center);
    }
}
