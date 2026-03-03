<?php

namespace App\Filament\Resources\Perusahaans\Pages;

use App\Filament\Resources\Perusahaans\PerusahaanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreatePerusahaan extends CreateRecord
{
    protected static string $resource = PerusahaanResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected bool $creationFailed = false;
    public function afterCreate()
    {
        Log::info($this->record);
        try {
            $user = $this->record->user->first();
            $user?->assignRole('manager');

            if (empty($this->record->logo)) {
                $this->record->logo = 'perusahaan/default_logo.png';
                $this->record->save();
            }
            
            Notification::make()
                ->title('Sukses')
                ->body('berhasil melakukan registrasi perusahaan baru')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            Log::info($th->getMessage());

            if ($this->record->exists) {
                $this->record->delete();
            }
            $this->creationFailed = true;

            Notification::make()
                ->title('Ups')
                ->body('gagal melakukan registrasi perusahaan baru')
                ->danger()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        if ($this->creationFailed) {
            return $this->getResource()::getUrl('create');
        }
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
