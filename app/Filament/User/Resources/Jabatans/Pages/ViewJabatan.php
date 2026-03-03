<?php

namespace App\Filament\User\Resources\Jabatans\Pages;

use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Resources\Jabatans\JabatanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJabatan extends ViewRecord
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
             EditButtonComponen::make()
        ];
    }
}
