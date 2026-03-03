<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\Imports\KaryawanImporter;
use App\Filament\User\Componen\Button\AddButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\ImportButtonComponen;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Filament\User\Widgets\KaryawanWidget;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Shift;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ListKaryawans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = KaryawanResource::class;

   
    protected function getHeaderWidgets(): array
    {
        return [
            KaryawanWidget::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [

            ImportButtonComponen::make()
                ->importer(KaryawanImporter::class)
                ->label('Import Karyawan')
                ->modalHeading('Import Data Karyawan')
                ->options([
                    'perusahaan_id' => auth()->user()->perusahaan_id
                ])
                ->modalDescription('  pastikan file csv anda valid dengan data example csv, pastikan juga nama shift, nama jabatan sama dengan yang terdaftar di sistem atau kosongkan saja bila tidak mempunyai shift dan jabatan. perhatikan format tipe karyawan, status karyawan, status dinas sama dengan yang di sistem')
                ->extraModalFooterActions([
                    Action::make('download-example-csv')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(
                            fn() =>
                            asset('storage/template/karyawan_example.csv'),
                            shouldOpenInNewTab: true
                        ),
                ]),

            AddButtonComponen::make()
        ];
    }
}
