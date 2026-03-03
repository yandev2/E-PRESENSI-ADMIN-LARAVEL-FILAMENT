<?php

namespace App\Filament\User\Resources\Shifts\Pages;

use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\ViewRecord;

class ViewShift extends ViewRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditButtonComponen::make()
        ];
    }
}
