<?php

namespace App\Filament\User\Resources\Kehadirans\Pages;

use App\Filament\User\Resources\Kehadirans\KehadiranResource;
use App\Filament\User\Widgets\PresensiTodayWidget;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListKehadirans extends ListRecords
{

    use ExposesTableToWidgets;
    protected static string $resource = KehadiranResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Kehadiran Hari Ini';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PresensiTodayWidget::class
        ];
    }
}
