<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Presensi;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticKetidakHadiran extends ChartWidget
{
    use InteractsWithPageFilters;
    protected ?string $heading = '📶 Analytic Ketidakhadiran';

    protected function getData(): array
    {
        $date = $this->pageFilters['date'] ?? Carbon::now();
        $tipe_karyawan = $this->pageFilters['tipe_karyawan'] ?? null;
        $status_dinas = $this->pageFilters['status_dinas'] ?? null;
        $jabatan_id = $this->pageFilters['jabatan_id'] ?? null;
        $shift_id = $this->pageFilters['shift_id'] ?? null;
        $kantor_id = $this->pageFilters['kantor_id'] ?? null;

        $queryPresensi = Presensi::query()
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

        $queryKaryawan = Karyawan::query();
        if (!empty($tipe_karyawan)) {
            $queryKaryawan->where('tipe_karyawan', $tipe_karyawan);
        }
        if (!empty($status_dinas)) {
            $queryKaryawan->where('status_dinas', $status_dinas);
        }
        if (!empty($jabatan_id)) {
            $queryKaryawan->where('jabatan_id', $jabatan_id);
        }
        if (!empty($shift_id)) {
            $queryKaryawan->where('shift_id', $shift_id);
        }
        if (!empty($kantor_id)) {
            $queryKaryawan->where('kantor_id', $kantor_id);
        }

        $queryHadir = $queryPresensi->where('status_masuk', 'H')->count();

        $queryTidakHadir = $queryKaryawan->whereDoesntHave('presensi', function ($q) use ($date) {
            $q->whereDate('tanggal', $date);
        })->count();

        $toRgba = function ($hex, $opacity = 0.5) {
            [$r, $g, $b] = sscanf($hex, "#%02x%02x%02x");
            return "rgba($r, $g, $b, $opacity)";
        };
        return [
            'datasets' => [
                [
                    'backgroundColor' => [
                        $toRgba('#14c904'),  // hijau transparan
                        $toRgba('#c90404'),   // merah transparan
                    ],
                    'border' => 1,
                    'borderColor' => [
                        '#14c904',
                        '#c90404',   // merah transparan
                    ],

                    'data' => [$queryHadir ?? 0, $queryTidakHadir ?? 0],
                ],
            ],
            'labels' => ['Hadir', 'Tidak Hadir'],
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
