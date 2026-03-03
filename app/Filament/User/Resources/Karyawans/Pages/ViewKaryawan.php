<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewKaryawan extends ViewRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditButtonComponen::make()
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Karyawan';
    }
    public function getTitle(): string|Htmlable
    {
        return $this->record->user->name;
    }
}
