<?php

namespace App\Filament\Resources\SalaryRecaps\Pages;

use App\Filament\Resources\SalaryRecaps\SalaryRecapsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaryRecaps extends ListRecords
{
    protected static string $resource = SalaryRecapsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
