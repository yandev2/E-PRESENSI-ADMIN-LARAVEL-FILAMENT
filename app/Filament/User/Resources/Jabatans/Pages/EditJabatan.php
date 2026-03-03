<?php

namespace App\Filament\User\Resources\Jabatans\Pages;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Jabatans\JabatanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJabatan extends EditRecord
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewButtonComponen::make(),
            DeleteButtonComponen::make()
        ];
    }
}
