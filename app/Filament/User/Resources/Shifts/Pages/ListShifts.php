<?php

namespace App\Filament\User\Resources\Shifts\Pages;

use App\Filament\User\Componen\Button\AddButtonComponen;
use App\Filament\User\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\ListRecords;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
             AddButtonComponen::make()
        ];
    }
}
