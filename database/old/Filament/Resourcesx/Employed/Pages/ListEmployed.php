<?php

namespace App\Filament\Resources\Employed\Pages;

use App\Filament\Resources\Employed\EmployedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployed extends ListRecords
{
    protected static string $resource = EmployedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Add Employee')
            ->icon('heroicon-o-user-plus'),
        ];
    }
}
