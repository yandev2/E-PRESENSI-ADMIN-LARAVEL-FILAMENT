<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\DeleteAction;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class DeleteButtonComponen
{
    public static function make(): DeleteAction
    {
        return DeleteAction::make()
            ->color('danger')
            ->button()
            ->icon(Heroicon::Trash)
            ->requiresConfirmation()
            ->modalHeading('KONFIRMASI')
            ->modalWidth(Width::Small)
            ->modalDescription(fn($record)=>'konfirmasi untuk menghapus data')
            ->label('Hapus');
    }
}
