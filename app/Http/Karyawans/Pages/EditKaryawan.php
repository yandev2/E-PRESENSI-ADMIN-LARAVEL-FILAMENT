<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    //   protected function mutateFormDataBeforeFill(array $data): array
    //   {
    //       $record = $this->record;
    //
    //       if ($record->user) {
    //           $data['nama']     = $record->user->name;
    //           $data['email']    = $record->user->email;
    //           $data['avatar']   = $record->user->avatar;
    //           $data['is_owner'] = $record->user->is_owner;
    //       }
    //       $data['password'] = null;
    //
    //       return $data;
    //   }
    //
    //   protected function handleRecordUpdate(Model $record, array $data): Model
    //   {
    //       $user = $record->user;
    //
    //       $userData = [
    //           'name'     => $data['nama'],
    //           'email'    => $data['email'],
    //           'is_owner' => $data['is_owner'],
    //       ];
    //
    //       if ($data['avatar'] !== ($user->avatar ?? null)) {
    //           if ($user->avatar) {
    //               Storage::disk('public')->delete($user->avatar);
    //           }
    //           $user->avatar = $data['avatar'];
    //       }
    //
    //       if (! empty($data['password'])) {
    //           $userData['password'] = $data['password'];
    //       }
    //
    //       $user->fill($userData)->save();
    //
    //       $user->update($userData);
    //
    //       unset(
    //           $data['nama'],
    //           $data['email'],
    //           $data['password'],
    //           $data['avatar'],
    //           $data['is_owner']
    //       );
    //
    //       return parent::handleRecordUpdate($record, $data);
    //   }
}
