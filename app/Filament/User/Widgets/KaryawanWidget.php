<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\Karyawans\Pages\ListKaryawans;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KaryawanWidget extends StatsOverviewWidget
{
    use HasWidgetShield,  InteractsWithPageTable;

    protected ?string $heading = 'Karyawan Analytic';
    protected static bool $isLazy = true;

    public function getColumns(): array|int|null
    {
        return [
            'sm' => 1,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
        ];
    }

    protected function getTablePage(): string
    {
        return ListKaryawans::class;
    }

    protected function getStats(): array
    {
        $jumlahKaryawan =  $this->getPageTableQuery()->count();
        $lakilaki = $this->getPageTableQuery()->where('jenis_kelamin', 'l')->count();
        $perempuan = $this->getPageTableQuery()->where('jenis_kelamin', 'p')->count();
        $rataRataGender = $lakilaki < $perempuan ? 'Perempuan' : 'Laki Laki';
        $karyawans = $this->getPageTableQuery()->get();
        $avgUsia = $karyawans->avg('usia');
        $avgUsiaLaki =  $karyawans->where('jenis_kelamin', 'l')->avg('usia');
        $avgUsiaPerempuan =  $karyawans->where('jenis_kelamin', 'p')->avg('usia');
        $datas = [
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Karyawan',
                "value" => $jumlahKaryawan . " Karyawan",
                "keys_quey" => null,
                "query" => null,
                "description" => "$lakilaki karyawan laki-laki dan $perempuan karyawan perempuan",
                "attr" => 'success',
            ],
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Gender',
                "value" => $jumlahKaryawan . " Karyawan",
                "keys_quey" => null,
                "query" => null,
                "description" => "rata rata karyawan adalah $rataRataGender",
                "attr" => 'warning',
            ],
            [
                "icon" => 'heroicon-o-users',
                "key" => 'Usia',
                "value" => round($avgUsia) . ' Tahun',
                "keys_quey" => null,
                "query" => null,
                "description" => "rata rata usia karyawan laki laki adalah " . round($avgUsiaLaki) . " tahun dan " . round($avgUsiaPerempuan) . " tahun usia karyawan perempuan",
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
