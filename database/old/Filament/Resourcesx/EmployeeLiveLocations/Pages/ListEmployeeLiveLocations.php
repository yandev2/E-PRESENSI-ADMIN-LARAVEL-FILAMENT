<?php

namespace App\Filament\Resources\EmployeeLiveLocations\Pages;

use App\Filament\Resources\EmployeeLiveLocations\EmployeeLiveLocationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeLiveLocations extends ListRecords
{
    protected static string $resource = EmployeeLiveLocationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
