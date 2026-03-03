<?php

namespace App\Filament\User\Resources\Presensis\Pages;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Resources\Presensis\PresensiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPresensi extends EditRecord
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteButtonComponen::make()
        ];
    }
}
