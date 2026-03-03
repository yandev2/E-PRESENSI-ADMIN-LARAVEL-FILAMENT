<?php

namespace App\Filament\User\Resources\Presensis\Pages;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Resources\Presensis\PresensiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPresensi extends ViewRecord
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteButtonComponen::make()
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Presensi';
    }
    public function getTitle(): string|Htmlable
    {
        return 'Detail presensi '. $this->record->karyawan?->user?->name?? '';
    }
}
