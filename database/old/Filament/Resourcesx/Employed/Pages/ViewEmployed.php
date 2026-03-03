<?php

namespace App\Filament\Resources\Employed\Pages;

use App\Filament\Resources\Employed\EmployedResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewEmployed extends ViewRecord
{
    protected static string $resource = EmployedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->icon(Heroicon::PencilSquare),
        ];
    }
}
