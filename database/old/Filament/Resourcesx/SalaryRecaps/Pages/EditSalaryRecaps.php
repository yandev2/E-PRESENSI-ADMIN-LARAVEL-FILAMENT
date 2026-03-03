<?php

namespace App\Filament\Resources\SalaryRecaps\Pages;

use App\Filament\Resources\SalaryRecaps\SalaryRecapsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalaryRecaps extends EditRecord
{
    protected static string $resource = SalaryRecapsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
