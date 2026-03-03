<?php

namespace App\Filament\App\Resources\Users\Pages;

use App\Filament\App\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected bool $creationFailed = false;

    public function getTitle(): string|Htmlable
    {
        return 'Create Employee';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
    protected function afterCreate(): void
    {
        $user = $this->record;
        try {

            DB::beginTransaction();
            $user->assignRole('employee');
            $user->company_id = auth()->user()->company->id;
            $user->save();
            DB::commit();

            Notification::make()
                ->title('Sukses')
                ->body('berhasil menambahkan karyawan baru')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($user?->exists) {
                $user->delete();
            }

            $this->creationFailed = true;
            Notification::make()
                ->title('Ops')
                ->body($th->getMessage())
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
