<?php

namespace App\Filament\Resources\Employed\Pages;

use App\Filament\Resources\Employed\EmployedResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployed extends EditRecord
{
    protected static string $resource = EmployedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
