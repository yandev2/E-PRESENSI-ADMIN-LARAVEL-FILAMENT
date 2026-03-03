<?php

namespace App\Filament\Resources\EmployeeLiveLocations\Pages;

use App\Filament\Resources\EmployeeLiveLocations\EmployeeLiveLocationsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeLiveLocations extends EditRecord
{
    protected static string $resource = EmployeeLiveLocationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
