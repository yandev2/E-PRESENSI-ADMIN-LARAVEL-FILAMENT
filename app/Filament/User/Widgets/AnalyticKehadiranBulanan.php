<?php

namespace App\Filament\User\Widgets;

use App\Models\Presensi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class AnalyticKehadiranBulanan extends ChartWidget
{
    use HasFiltersSchema;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): string | Htmlable | null
    {
        return '📊 Kehadiran karyawan Tahunan '; //. $this->filters['year']?? Carbon::now()->year;
    }

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('year')
                ->label('Tahun')
                ->prefixIcon(Heroicon::CalendarDateRange)
                ->prefixIconColor('info')
                ->options(
                    collect(range(Carbon::now()->year, Carbon::now()->year - 20))
                        ->mapWithKeys(fn($year) => [$year => $year])
                        ->toArray()
                )
                ->default(Carbon::now()->year)
                ->searchable()
                ->required()
        ]);
    }

    protected function getData(): array
    {
        $year =  $this->filters['year'] ?? Carbon::now()->year;

        $labels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $query = Presensi::selectRaw('CAST(EXTRACT(MONTH FROM tanggal) AS INTEGER) as bulan, status_masuk, COUNT(*) as total')
            ->whereRaw('EXTRACT(YEAR FROM tanggal) = ?', [$year])
            ->where('perusahaan_id', auth()->user()->perusahaan_id)
            ->groupByRaw('EXTRACT(MONTH FROM tanggal), status_masuk');
        $presensi = $query->get();

        $statuses = [
            'H' => ['label' => 'Hadir', 'color' => '#14c904'],
            'I' => ['label' => 'Izin', 'color' => '#c9a504'],
            'S' => ['label' => 'Sakit', 'color' => '#c90404'],
        ];

        $data = [];
        foreach ($statuses as $kode => $info) {
            $data[$info['label']] = array_fill(1, 12, 0);
        }

        foreach ($presensi as $p) {
            $bulan = (int) $p->bulan;
            $statusKey = strtoupper($p->status_masuk);
            $statusInfo = $statuses[$statusKey] ?? null;

            if ($statusInfo) {
                $data[$statusInfo['label']][$bulan] = $p->total;
            }
        }

        $datasets = collect($data)->map(function ($values, $label) use ($statuses) {
            $color = collect($statuses)->firstWhere('label', $label)['color'] ?? '#888888';
            $toRgba = function ($hex, $opacity = 0.2) {
                [$r, $g, $b] = sscanf($hex, "#%02x%02x%02x");
                return "rgba($r, $g, $b, $opacity)";
            };
            return [
                'label' => $label,
                'data' => array_values($values),
                'backgroundColor' =>$toRgba($color, 0.2),
                'borderWidth' => 1,
                'borderColor' => $color


            ];
        })->values()->toArray();

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getOptions(): array
    {
        return [

            'responsive' => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'boxWidth' => 17,
                        'boxHeight' => 17,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
