<?php

namespace App\Filament\User\Resources\Jabatans\Pages;

use App\Filament\Imports\JabatanImporter;
use App\Filament\User\Componen\Button\AddButtonComponen;
use App\Filament\User\Componen\Button\ImportButtonComponen;
use App\Filament\User\Resources\Jabatans\JabatanResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJabatans extends ListRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportButtonComponen::make()
                ->importer(JabatanImporter::class)
                ->label('Import Jabatan')
                ->modalHeading('Import Data Jabatan')
                ->modalDescription('  pastikan file csv anda valid dengan data example csv.')
                ->extraModalFooterActions([
                    Action::make('download-example-csv')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(
                            fn() =>
                            asset('storage/template/jabatan_example.csv'),
                            shouldOpenInNewTab: true
                        ),
                ]),

            AddButtonComponen::make()
        ];
    }
}
