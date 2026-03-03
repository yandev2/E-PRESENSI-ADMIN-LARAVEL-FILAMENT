<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\Kehadirans\KehadiranResource;
use App\Filament\User\Resources\Kehadirans\Pages\ListKehadirans;
use App\Models\Presensi;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class PresensiTodayWidget extends StatsOverviewWidget
{

    use HasWidgetShield, InteractsWithPageTable;

    protected ?string $heading = 'Presensi Today Analytic';
    protected static bool $isLazy = true;

    public function getColumns(): array|int|null
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 4,
        ];
    }

    protected function getTablePage(): string
    {
        return ListKehadirans::class;
    }

    protected function getStats(): array
    {
        $izin = $this->getPageTableQuery()->where('status_masuk', 'I')->count();
        $sakit = $this->getPageTableQuery()->where('status_masuk', 'S')->count();
        $hadir = $this->getPageTableQuery()->where('status_masuk', 'H')->count();
        $tidak_absen_keluar = $this->getPageTableQuery()->where('status_masuk', 'H')->where('status_keluar', null)->count();
        $datas = [
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Hadir',
                "value" => $hadir,
                "keys_quey" => null,
                "query" => null,
                "description" => 'jumlah karyawan hadir',
                "attr" => 'success',
            ],
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Sakit',
                "value" => $sakit,
                "keys_quey" => null,
                "query" => null,
                "description" => 'jumlah karyawan sakit',
                "attr" => 'warning',
            ],
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Izin',
                "value" => $izin,
                "keys_quey" => null,
                "query" => null,
                "description" => 'jumlah karyawan izin',
                "attr" => 'info',
            ],
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Tidak absen keluar',
                "value" => $tidak_absen_keluar,
                "keys_quey" => null,
                "query" => null,
                "description" => 'jumlah karyawan tidak absen keluar',
                "attr" => 'danger',
            ],
        ];

        $stats = [];

        foreach ($datas as $d) {
            $stats[] =  Stat::make($d['key'], '')
                ->value($d['value'])
                ->description($d['description'])
                ->descriptionColor('success')
                ->icon($d['icon'])
                ->chart([10, 10])
                ->extraAttributes(['class' => 'stats-' . $d['attr']]);
        }

        return $stats;
    }
}
