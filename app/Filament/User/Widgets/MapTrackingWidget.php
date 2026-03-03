<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\LiveLocationkaryawans\Pages\ManageLiveLocationkaryawans;
use App\Models\LiveLocationkaryawan;
use EduardoRibeiroDev\FilamentLeaflet\Enums\TileLayer;
use EduardoRibeiroDev\FilamentLeaflet\Support\Markers\Marker;
use EduardoRibeiroDev\FilamentLeaflet\Widgets\MapWidget;
use Filament\Widgets\ChartWidget;
use EduardoRibeiroDev\FilamentLeaflet\Support\Shapes\Circle;
use Filament\Notifications\Notification;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MapTrackingWidget extends MapWidget
{

    use InteractsWithPageTable;
    protected int | string | array $columnSpan = 'full';
    protected bool $hasAttributionControl = true;
    protected bool $hasScaleControl = true;
    protected bool $hasZoomControl = true;
    protected bool $hasFullscreenControl = true;
    protected bool $hasSearchControl = true;
    protected static ?string $pollingInterval = '5s';
    protected int $defaultZoom = 30;
    protected function getMapCenter(): array
    {
        $perusahaan = auth()->user()->perusahaan;
        if (!empty($perusahaan->lokasi)) {
            $lokasiArray = explode(',', $perusahaan->lokasi);
            return $lokasiArray;
        }
        else{
            return [-3.1606487928814047, 102.93786372785712];
        }
    }

    protected TileLayer|string|array $tileLayersUrl = [
        'Street Map' => TileLayer::OpenStreetMap,
        'Satellite' => TileLayer::GoogleSatellite,
        'Custom' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
    ];

    protected array $tileLayerOptions = [
        'OpenStreetMap' => [
            'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        ],
        'OpenTopoMap' => [
            'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, SRTM | Map style © <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
        ],
    ];

    public function getHeading(): string
    {
        return 'Lokasi karyawan';
    }

    protected function getTablePage(): string
    {
        return ManageLiveLocationkaryawans::class;
    }
    protected $listeners = ['refresh' => '$refresh'];

    protected function getMarkers(): array
    {
        $perusahaan = auth()->user()->perusahaan;
        $kantor = $perusahaan->kantor;

        $lokasiKaryawan =  $this->getPageTableQuery()->with('karyawan')->get(); //->whereDate('update_at', Carbon::now());
        $markers = [];
        if (empty($lokasiKaryawan)) {
            return [];
        }

        foreach ($lokasiKaryawan as $data) {
            $avatar = $data?->karyawan?->user?->avatar == null ? asset('storage/karyawan/default_sys_l.png') : Storage::url($data?->karyawan?->user?->avatar);
            $markers[] = Marker::make($data->latitude, $data->longitude)
                ->blue()
                ->icon($avatar, [40, 40])
                ->popupOptions(['maxWidth' => 300])
                ->popupContent(fn() => $data->karyawan?->kantor?->nama_kantor ?? '')
                ->popupTitle($data?->karyawan?->user?->name ?? 'unknow')
                ->popupFields([
                    'kantor' => $data->karyawan?->kantor?->nama_kantor ?? '',
                    'phone' =>  $data->karyawan?->nomor_telp ?? '',
                    'jabatan' =>  $data->karyawan?->jabatan?->nama_jabatan ?? '',
                ]);
        }
        if (!$kantor->isEmpty()) {
            foreach ($kantor as $data) {
                $lokasiArray = explode(',', $data->lokasi);
                $markers[] =    Marker::make($lokasiArray[0], $lokasiArray[1])
                    ->popupTitle($data->nama_kantor ?? 'unknow')
                    ->red();
            }
        }

        if (!empty($perusahaan->lokasi)) {
            $lokasiArray = explode(',', $perusahaan->lokasi);
            $markers[] = Marker::make($lokasiArray[0], $lokasiArray[1])
                ->icon($perusahaan?->logo == null ? asset('storage/perusahaan/default_logo.png') : Storage::url($perusahaan->logo), [40, 40])
                ->popupTitle($perusahaan->nama_perusahaan ?? 'unknow');
        }
        return $markers;
    }

    protected function getShapes(): array
    {
        $perusahaan = auth()->user()->perusahaan;
        $kantor = $perusahaan->kantor;

        $markers = [];
        if ($kantor->isEmpty()) {
            return [];
        }

        if (!empty($perusahaan->lokasi)) {
            $lokasiArray = explode(',', $perusahaan->lokasi);
            $markers[] =   Circle::make($lokasiArray[0], $lokasiArray[1])
                ->title($perusahaan?->nama_perusahaan ?? 'Belum di setting')
                ->blue()
                ->fillGreen()
                ->fillOpacity(0.2)
                ->radius(50);
        }

        foreach ($kantor as $data) {
            $lokasiArray = explode(',', $data->lokasi);
            $markers[] =   Circle::make($lokasiArray[0], $lokasiArray[1])
                ->title($data->nama_kantor)
                ->blue()
                ->fillBlue()
                ->fillOpacity(0.2)
                ->radius($data?->radius ?? 20);
        }
        return $markers;
    }

    public function handleMapClick(float $latitude, float $longitude): void
    {
        Notification::make()
            ->title('Map clicked')
            ->body("Coordinates: {$latitude}, {$longitude}")
            ->send();
    }
}
