<?php

namespace App\Filament\Resources\Perusahaans\Pages;

use App\Filament\Resources\Perusahaans\PerusahaanResource;
use App\Models\PresensiSetting;
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
            PresensiSetting::create([
                'perusahaan_id' => $this->record->id,
                'batas_waktu_pengajuan_izin' => '23:59:00',
                'batas_waktu_sebelum_absen_masuk' => 1,
                'batas_waktu_sesudah_absen_masuk' => 1,
                'batas_waktu_sebelum_absen_keluar' => 1,
                'batas_waktu_sesudah_absen_keluar' => 1,
                'batas_waktu_keterlambatan' => 30,
                'disable_presensi' => false,
            ]);

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
