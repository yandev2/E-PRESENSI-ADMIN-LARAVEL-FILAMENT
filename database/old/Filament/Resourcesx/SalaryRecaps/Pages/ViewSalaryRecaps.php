<?php

namespace App\Filament\Resources\SalaryRecaps\Pages;

use App\Filament\Resources\SalaryRecaps\SalaryRecapsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalaryRecaps extends ViewRecord
{
    protected static string $resource = SalaryRecapsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
