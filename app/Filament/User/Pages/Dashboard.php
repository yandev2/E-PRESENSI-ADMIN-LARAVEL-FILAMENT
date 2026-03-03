<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\AnalyticKehadiranMasuk;
use App\Filament\User\Widgets\AnalyticKehadiranTahunan;
use App\Filament\User\Widgets\AnalyticKehadiranKeluar;
use App\Filament\User\Widgets\AnalyticKetidakHadiran;
use App\Filament\User\Widgets\DashboardCountOverlay;
use App\Filament\User\Widgets\OverlayDashboard;

use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Shift;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;

use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;


class Dashboard extends BaseDashboard
{
    use HasPageShield, HasFiltersForm;

    //protected string $view = 'filament.user.pages.dashboard';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    public function getTitle(): string|Htmlable
    {

        return 'Dashboard';
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Analytic Kehadiran')
                    ->extraAttributes(['class' => 'filter_dashboard'])
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 6
                    ])
                    ->headerActions([
                        Action::make('resetFilter')
                            ->label('Reset Filter')
                            ->color('warning')
                            ->badge()
                            ->size(Size::Medium)
                            ->action(function () use ($schema) {
                                $schema->fill([]);
                            }),
                    ])
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->native(false)
                            ->default(null)
                            ->prefixIcon(Heroicon::CalendarDateRange)
                            ->prefixIconColor('info')
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['date'] = $state),

                        Select::make('tipe_karyawan')
                            ->label('Tipe karyawan')
                            ->placeholder('')
                            ->native(false)
                            ->options([
                                'tetap' => 'tetap',
                                'magang' => 'magang',
                                'kontrak' => 'kontrak',
                                'paruh_waktu' => 'paruh waktu'
                            ])
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['tipe_karyawan'] = $state),

                        Select::make('status_dinas')
                            ->label('Status dinas')
                            ->placeholder('')
                            ->native(false)
                            ->options([
                                'luar' => 'dalam',
                                'dalam' => 'luar',
                            ])
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['status_dinas'] = $state),

                        Select::make('jabatan_id')
                            ->placeholder('')
                            ->native(false)
                            ->label('Jabatan')
                            ->searchable()
                            ->options(Jabatan::pluck('nama_jabatan', 'id'))
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['jabatan_id'] = $state),
                        Select::make('shift_id')
                            ->placeholder('')
                            ->prefixIcon(Heroicon::OutlinedClock)
                            ->native(false)
                            ->label('Shift')
                            ->searchable()
                            ->options(Shift::pluck('nama_shift', 'id'))
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['shift_id'] = $state),

                        Select::make('kantor_id')
                            ->placeholder('')
                            ->prefixIcon(Heroicon::BuildingOffice)
                            ->native(false)
                            ->label('Kantor')
                            ->searchable()
                            ->options(Kantor::pluck('nama_kantor', 'id'))
                            ->live()
                            ->afterStateUpdated(fn($state) => $this->filters['kantor_id'] = $state),
                    ]),
            ]);
    }


    public function getHeaderWidgets(): array
    {
        return [
            OverlayDashboard::class,
            DashboardCountOverlay::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            AnalyticKehadiranMasuk::class,
            AnalyticKehadiranKeluar::class,
            AnalyticKetidakHadiran::class,
        ];
    }
    public function getColumns(): int|array
    {
        return [
            "sm" => 1,
            "md" => 1,
            "lg" => 3,
            "xl" => 3,
            "2xl" => 3
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            AnalyticKehadiranTahunan::class
        ];
    }
    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }
}
