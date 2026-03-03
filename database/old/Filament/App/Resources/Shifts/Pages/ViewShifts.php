<?php

namespace App\Filament\App\Resources\Shifts\Pages;

use App\Filament\App\Resources\Shifts\ShiftsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShifts extends ViewRecord
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
