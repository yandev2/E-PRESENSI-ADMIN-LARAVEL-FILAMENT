<?php

namespace App\Filament\Resources\EmployeeLiveLocations\Pages;

use App\Filament\Resources\EmployeeLiveLocations\EmployeeLiveLocationsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployeeLiveLocations extends ViewRecord
{
    protected static string $resource = EmployeeLiveLocationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
