<?php

namespace App\Filament\App\Resources\Karyawans\Pages;

use App\Filament\App\Resources\Karyawans\KaryawanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
