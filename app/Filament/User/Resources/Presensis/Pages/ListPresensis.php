<?php

namespace App\Filament\User\Resources\Presensis\Pages;

use App\Filament\User\Resources\Presensis\PresensiResource;
use App\Filament\User\Widgets\PresensiWidget;
use App\Models\Jabatan;
use App\Models\Presensi;
use App\Models\Shift;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;

class ListPresensis extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = PresensiResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PresensiWidget::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_presensi')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->modalIcon('heroicon-o-arrow-down-on-square-stack')
                ->modalWidth('md')
                ->color('success')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Export Presensi Karyawan')
                ->modalDescription('export data presensi karyawan dalam bentuk pdf atau exel')
                ->modalWidth('md')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('type')
                                ->label('Tipe File')
                                ->native(false)
                                ->required()
                                ->columnSpanFull()
                                ->placeholder('')
                                ->options([
                                    'exel' => 'Exel',
                                    'pdf' => 'Pdf'
                                ]),
                            TextInput::make('name')
                                ->label('Nama File')
                                ->columnSpanFull()
                                ->prefixIcon(Heroicon::OutlinedDocument)
                                ->prefixIconColor('info')
                                ->required(),
                            DatePicker::make('bulan')
                                ->required()
                                ->native(false)
                                ->displayFormat('F Y')
                                ->format('Y-m')
                                ->closeOnDateSelection()
                                ->label('Pilih Bulan dan Tahun'),

                            Select::make('jabatan')
                                ->label('Jabatan')
                                ->native(false)
                                ->required()
                                ->columnSpan(1)
                                ->placeholder('')
                                ->options(options: collect(['all' => 'All'])
                                    ->merge(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))),
                            Select::make('shift')
                                ->label('Shift')
                                ->native(false)
                                ->required()
                                ->columnSpan(1)
                                ->placeholder('')
                                ->options(options: collect(['all' => 'All'])
                                    ->merge(Shift::pluck('nama_shift', 'nama_shift'))),

                            Select::make('status_dinas')
                                ->label('Status Dinas')
                                ->native(false)
                                ->required()
                                ->placeholder('')
                                ->options([
                                    'all' => 'All',
                                    'luar' => 'Dinas Luar',
                                    'dalam' => 'Dinas Dalam'
                                ]),
                        ])
                ])
                ->action(function ($data) {
                    $jabatan = $data['jabatan'];
                    $status_dinas = $data['status_dinas'];
                    $shift = $data['shift'];
                    $nameFile = $data['name'];
                    $type =  $data['type'];

                    $query = Presensi::query();
                    if (!empty($jabatan) && $jabatan !== 'all') {
                        $query->whereHas('jabatan', function ($q) use ($jabatan) {
                            $q->where('nama_jabatan', $jabatan);
                        });
                    }

                    if (!empty($shift) && $shift !== 'all') {
                        $query->whereHas('shift', function ($q) use ($shift) {
                            $q->where('nama_shift', $shift);
                        });
                    }

                    if (!empty($status_dinas) && $status_dinas !== 'all') {
                        $query->whereHas('karyawan', function ($q) use ($status_dinas) {
                            $q->where('status_dinas', $status_dinas);
                        });
                    }

                    if (empty($userIds)) {
                        Notification::make()
                            ->title('Gagal melakukan export')
                            ->body('Data Presensi yang dipilih tidak tersedia (Kosong)')
                            ->danger()
                            ->send();

                        return; 
                    }

                    $userIds = $query->pluck('id')->toArray();

                    return redirect()->route('export.presensi', [
                        'id' => implode(',', $userIds),
                        'type' => $type,
                        'name_file' => $nameFile,
                    ]);
                })
        ];
    }
}
