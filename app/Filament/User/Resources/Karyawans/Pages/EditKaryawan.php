<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;
    protected bool $creationFailed = false;

    public function getBreadcrumb(): string
    {
        return  $this->record->user->name;
    }
    public function getTitle(): string|Htmlable
    {
        return 'Edit Data';
    }
    protected function getHeaderActions(): array
    {
        return [
            ViewButtonComponen::make(),
            DeleteButtonComponen::make()
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return DB::transaction(function () use ($record, $data) {

                $user = $record->user;
                $userData = $data['user'] ?? [];

                $newAvatar = $userData['avatar'] ?? null;
                $oldAvatar = $user->avatar;

                $deleteOldAvatar = false;

                if (empty($newAvatar) && ! empty($oldAvatar)) {
                    $deleteOldAvatar = true;
                    $userData['avatar'] = null;
                }

                if (! empty($newAvatar) && $newAvatar !== $oldAvatar) {
                    $deleteOldAvatar = true;
                }

                if (! empty($userData['password'])) {
                    $userData['password'] = Hash::make($userData['password']);
                } else {
                    unset($userData['password']);
                }

                $user->update($userData);

                unset($data['user']);
                $updatedRecord = parent::handleRecordUpdate($record, $data);

                if (!in_array($oldAvatar, ['karyawan/default_sys_l.jpg', 'karyawan/default_sys.jpg'])) {
                    DB::afterCommit(function () use ($deleteOldAvatar, $oldAvatar) {
                        if ($deleteOldAvatar && $oldAvatar) {
                            $this->deleteFile($oldAvatar);
                        }
                    });
                }

                return $updatedRecord;
            });
        } catch (\Throwable $th) {
            $this->creationFailed = true;
            throw $th;
        }
    }

    protected function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        if ($user) {
            $data['user'] = [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ];
        }
        return $data;
    }

    protected function getSavedNotification(): ?Notification
    {
        return $this->creationFailed == false
            ? Notification::make()
            ->success()
            ->title('Okay')
            ->body('data karyawan berhasil di update')
            : Notification::make()
            ->success()
            ->title('Ups')
            ->body('terjadi kesalahan saat mengupdate data karyawan');;
    }
}
