<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;
    protected function afterCreate(): void
    {
        $this->record->assignRole('karyawan');
        $this->record->perusahaan_id = auth()->user()->perusahaan_id;
        $this->record->save();
    }
    //  protected bool $creationFailed = false;
    //  protected string $errorMessage = '';
    //  protected function handleRecordCreation(array $data): Model
    //  {
    //      try {
    //          $path = $data['avatar'];
    //          $user = $this->createUser($data);
    //          //Log::info($user);
    //          //Log::info($path);
    //          unset(
    //              $data['nama'],
    //              $data['email'],
    //              $data['password'],
    //              $data['is_owner'],
    //              $data['avatar'],
    //          );
    //
    //          $data['user_id'] = $user->id;
    //
    //          return static::getModel()::create($data);
    //      } catch (\Throwable $th) {
    //          if ($path && Storage::disk('public')->exists($path)) {
    //              Storage::disk('public')->delete($path);
    //          }
    //          //Log::info($th->getMessage());
    //
    //          if ($user->exists()) {
    //              $user->delete();
    //          }
    //          $this->errorMessage = "terjadi kesalahan saat menambahkan data karyawan. Error " . $th->getMessage();
    //          $this->creationFailedv = true;
    //          $this->halt();
    //          throw $th;
    //      }
    //  }
    //
    //  protected function createUser(array $data): User
    //  {
    //      try {
    //          $user = User::create([
    //              'perusahaan_id' => auth()->user()->perusahaan_id,
    //              'name' => $data['nama'],
    //              'email' => $data['email'],
    //              'password' => $data['password'],
    //              'is_owner' => $data['is_owner'],
    //              'avatar' => $data['avatar'],
    //          ]);
    //          $user->assignRole('karyawan');
    //          return $user;
    //      } catch (\Throwable $th) {
    //          throw $th;
    //      }
    //  }
    //
    //  protected function getCreatedNotification(): ?Notification
    //  {
    //      return $this->creationFailed == false
    //          ? Notification::make()
    //          ->success()
    //          ->title('Okay')
    //          ->body('karyawan baru berhasil ditambahkan')
    //          : Notification::make()
    //          ->success()
    //          ->title('Ups')
    //          ->body($this->errorMessage);
    //  }
}
