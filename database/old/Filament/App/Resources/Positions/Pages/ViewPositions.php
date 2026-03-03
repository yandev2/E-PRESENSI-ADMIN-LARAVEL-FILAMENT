<?php

namespace App\Filament\App\Resources\Positions\Pages;

use App\Filament\App\Resources\Positions\PositionsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPositions extends ViewRecord
{
    protected static string $resource = PositionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
