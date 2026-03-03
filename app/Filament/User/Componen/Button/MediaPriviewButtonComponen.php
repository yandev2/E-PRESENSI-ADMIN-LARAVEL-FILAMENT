<?php

namespace App\Filament\User\Componen\Button;

use Filament\Support\Icons\Heroicon;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

class MediaPriviewButtonComponen
{
    public static function make(): MediaAction
    {
        return MediaAction::make('Preview')
            ->label('Preview')
            ->size('sm')
            ->color('info')
            ->button()
            ->modalWidth('full')
            ->icon('heroicon-o-eye')
            ->modalIcon(Heroicon::Document)
            ->media(fn($record) => str_replace(' ', '%20', Storage::url($record->file)))
            ->visible(function ($record) {
                $path = $record->file ?? null;
                if (! $path) return false;
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                return $extension === 'pdf';
            });
    }
}
