<?php

namespace App\Filament\User\Resources\Izins\Pages;

use App\Filament\User\Resources\Izins\IzinResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIzins extends ListRecords
{
    protected static string $resource = IzinResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
