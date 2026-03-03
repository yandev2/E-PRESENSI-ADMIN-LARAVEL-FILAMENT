<?php

namespace App\Filament\App\Resources\Positions\Pages;

use App\Filament\App\Resources\Positions\PositionsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Position')
                ->icon(Heroicon::Plus),
        ];
    }
}
