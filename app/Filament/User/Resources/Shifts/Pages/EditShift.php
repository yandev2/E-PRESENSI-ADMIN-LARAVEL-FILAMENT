<?php

namespace App\Filament\User\Resources\Shifts\Pages;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\EditRecord;

class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
           ViewButtonComponen::make(),
            DeleteButtonComponen::make()
        ];
    }
}
