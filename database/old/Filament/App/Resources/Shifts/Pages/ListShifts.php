<?php

namespace App\Filament\App\Resources\Shifts\Pages;

use App\Filament\App\Resources\Shifts\ShiftsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Shift')
                ->icon(Heroicon::Plus),
        ];
    }
}
