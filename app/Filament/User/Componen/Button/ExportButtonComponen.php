<?php

namespace App\Filament\User\Componen\Button;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;

class ExportButtonComponen
{
    public static function make(): Action
    {
        return BulkAction::make('export')
            ->icon('heroicon-o-arrow-up-on-square-stack')
            ->modalIcon('heroicon-o-arrow-up-on-square-stack')
            ->modalWidth('sm')
            ->color('success')
            ->modalAlignment(Alignment::Center)
            ->successNotificationTitle('Berhasil Export Data')
            ->failureNotificationTitle(function (int $successCount, int $totalCount): string {
                if ($successCount) {
                    return "{$successCount} of {$totalCount} data export";
                }

                return 'Failed to export any data';
            })
            ->schema([
                Select::make('type')
                    ->label('Tipe File')
                    ->native(false)
                    ->required()
                    ->columnSpan(1)
                    ->placeholder('')
                    ->options([
                        'exel' => 'Exel',
                        'pdf' => 'Pdf'
                    ])

            ]);
    }
}
