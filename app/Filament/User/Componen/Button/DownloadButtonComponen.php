<?php
namespace App\Filament\User\Componen\Button;
use Filament\Actions\Action;
class DownloadButtonComponen
{
    public static function make(): Action
    {
        return  Action::make('download')
            ->size('sm')
            ->color('success')
            ->button()
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn($record) => asset('storage/' . $record->file), shouldOpenInNewTab: true)
            ->visible(function ($record) {
                $path = $record->file ?? null;
                if (! $path) return false;
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                return $extension != 'pdf';
            });
    }
}
