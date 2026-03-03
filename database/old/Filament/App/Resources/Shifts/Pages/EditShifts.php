<?php

namespace App\Filament\App\Resources\Shifts\Pages;

use App\Filament\App\Resources\Shifts\ShiftsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShifts extends EditRecord
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
