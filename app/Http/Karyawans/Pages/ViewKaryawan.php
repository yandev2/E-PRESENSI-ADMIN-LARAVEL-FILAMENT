<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKaryawan extends ViewRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dokumen_karyawan')
                ->button()
                ->color('info')
               ->label('Dokumen Audit')->url(fn($record) => DokumenKaryawanPage::getUrl(['record' => $record])),
            EditAction::make(),
        ];
    }
}
