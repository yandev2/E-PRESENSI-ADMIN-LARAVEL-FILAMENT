<?php

namespace App\Filament\App\Resources\Shifts\Pages;

use App\Filament\App\Resources\Shifts\ShiftsResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateShifts extends CreateRecord
{
    protected static string $resource = ShiftsResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return
            Notification::make()
            ->title('Okay')
            ->body('berhasil menambahkan shift baru')
            ->success()
            ->send();
    }
}
