<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
    protected bool $creationFailed = false;
    public function afterCreate(): void
    {
        $form = $this->form->getRawState();
        $company = $this->record;

        try {
            DB::beginTransaction();
            Log::info($company);

            $user = User::create([
                'name'       => 'admin_' . $form['name'],
                'email'      => $form['email'],
                'password'   => Hash::make($form['password']),
                'company_id' => $company->id,
            ]);

            $user->assignRole('admin');

            DB::commit();

            Notification::make()
                ->title('Sukses')
                ->body('new company successfully added')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($company?->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            if ($company?->exists) {
                $company->delete();
            }

            report($th);

            Notification::make()
                ->title('Gagal')
                ->body('Gagal membuat user admin. Pastikan email belum digunakan.')
                ->danger()
                ->send();

            $this->creationFailed = true;
        }
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null; 
    }

    protected function getRedirectUrl(): string
    {
        if ($this->creationFailed) {
            return $this->getResource()::getUrl('create');
        }

        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
