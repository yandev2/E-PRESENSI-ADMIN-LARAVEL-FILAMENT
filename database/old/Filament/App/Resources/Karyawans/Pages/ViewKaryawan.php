<?php

namespace App\Filament\App\Resources\Karyawans\Pages;

use App\Filament\App\Resources\Karyawans\KaryawanResource;
use App\Filament\App\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKaryawan extends ViewRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }
}
