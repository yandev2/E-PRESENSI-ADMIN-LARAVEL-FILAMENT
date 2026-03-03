<?php

namespace App\Filament\User\Resources\LiveLocationkaryawans\Pages;

use App\Filament\User\Componen\Button\AddButtonComponen;
use App\Filament\User\Resources\LiveLocationkaryawans\LiveLocationkaryawanResource;
use App\Filament\User\Widgets\MapTrackingWidget;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;

class ManageLiveLocationkaryawans extends ManageRecords
{
    protected static string $resource = LiveLocationkaryawanResource::class;
    use ExposesTableToWidgets;
    protected function getHeaderActions(): array
    {
        return [
          //  Action::make('refresh')
          //      ->label('Refresh')
          //      ->icon(Heroicon::CircleStack)
          //      ->action(fn() =>   $this->dispatch('refresh'))
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MapTrackingWidget::class
        ];
    }
}
