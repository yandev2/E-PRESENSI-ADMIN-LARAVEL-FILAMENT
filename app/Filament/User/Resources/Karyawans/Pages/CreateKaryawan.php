<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;
    protected bool $creationFailed = false;

    protected function handleRecordCreation(array $data): Model
    {
        $path = $data['user']['avatar'];
        try {
            Log::info($data);
            $user  = $this->createUser($data);
            unset($data['user']);

            $data['user_id'] = $user->id;
            return static::getModel()::create($data);
        } catch (\Throwable $th) {
            Log::error($th);

            $this->deleteFile($path);

            if ($user->exists()) {
                $user->delete();
            }
            $this->creationFailed = true;
            $this->halt();

            throw $th;
        }
    }

    protected function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function createUser(array $data): User
    {
        $users = $data['user'];
        Log::info($users['avatar']);
        $user = User::create([
            'perusahaan_id' => auth()->user()->perusahaan_id,
            'name' => $users['name'],
            'email' => $users['email'],
            'password' => $users['password'],
            'is_owner' => $users['is_owner'],
            'avatar' => $users['avatar'] ??  $data['jenis_kelamin'] == 'l' ? 'karyawan/default_sys_l.jpg' : 'karyawan/default_sys.jpg',
        ]);
        $user->assignRole('karyawan');
        return $user;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return $this->creationFailed == false
            ? Notification::make()
            ->success()
            ->title('Okay')
            ->body('karyawan baru berhasil ditambahkan')
            : Notification::make()
            ->success()
            ->title('Ups')
            ->body('terjadi kesalahan saat menambahkan karyawan');
    }
}
