<?php

namespace App\Filament\User\Widgets;

use App\Models\Presensi;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticKehadiranMasuk extends ChartWidget
{

    use InteractsWithPageFilters;
    protected ?string $heading = '📶 Analytic Kehadiran Masuk';

    protected function getData(): array
    {

        $date = $this->pageFilters['date'] ?? Carbon::now();
        $tipe_karyawan = $this->pageFilters['tipe_karyawan'] ?? null;
        $status_dinas = $this->pageFilters['status_dinas'] ?? null;
        $jabatan_id = $this->pageFilters['jabatan_id'] ?? null;
        $shift_id = $this->pageFilters['shift_id'] ?? null;
        $kantor_id = $this->pageFilters['kantor_id'] ?? null;

        $query = Presensi::query()
            ->whereDate('tanggal', $date)
            ->whereHas('karyawan', function ($q) use ($tipe_karyawan, $status_dinas, $jabatan_id, $shift_id, $kantor_id) {
                if (!empty($tipe_karyawan)) {
                    $q->where('tipe_karyawan', $tipe_karyawan);
                }
                if (!empty($status_dinas)) {
                    $q->where('status_dinas', $status_dinas);
                }
                if (!empty($jabatan_id)) {
                    $q->where('jabatan_id', $jabatan_id);
                }
                if (!empty($shift_id)) {
                    $q->where('shift_id', $shift_id);
                }
                if (!empty($kantor_id)) {
                    $q->where('kantor_id', $kantor_id);
                }
            });

        $query->select('status_masuk', DB::raw('COUNT(*) as total'));

        $data = $query
            ->groupBy('status_masuk')
            ->pluck('total', 'status_masuk');
        $toRgba = function ($hex, $opacity = 0.5) {
            [$r, $g, $b] = sscanf($hex, "#%02x%02x%02x");
            return "rgba($r, $g, $b, $opacity)";
        };
        return [
            'datasets' => [
                [
                    'backgroundColor' => [
                        $toRgba('#c90404'),   // merah transparan
                        $toRgba('#c9a504'), // kuning transparan
                        $toRgba('#14c904'),  // hijau transparan
                    ],
                    'border' => 1,
                    'borderColor' => [
                        '#c90404',   // merah transparan
                        '#c9a504', // kuning transparan
                        '#14c904',  // hijau transparan
                    ],

                    'data' => [$data['S'] ?? 0, $data['I'] ?? 0, $data['H'] ?? 0],
                ],
            ],
            'labels' => ['Sakit', 'Izin', 'Hadir'],
        ];
    }


    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 17,
                        'boxHeight' => 17,
                        'font' => [
                            'size' => 12,
                        ],

                    ],
                ],
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
