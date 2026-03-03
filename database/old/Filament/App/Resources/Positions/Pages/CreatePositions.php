<?php

namespace App\Filament\App\Resources\Positions\Pages;

use App\Filament\App\Resources\Positions\PositionsResource;
use App\Models\PositionSalaries;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CreatePositions extends CreateRecord
{
    protected static string $resource = PositionsResource::class;
    protected bool $creationFailed = false;

    protected function getCreatedNotification(): ?Notification
    {
        return  null;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        
        try {
            DB::beginTransaction();

            $oldSalary = $record->salaries->skip(1)->first();
            if ($oldSalary) {
                $oldSalary->update([
                    "status" => "inactive",
                    'effective_to' => Carbon::parse($record->activeSalaries->effective_from),
                ]);
            }
            DB::commit();

            Notification::make()
                ->title('Sukses')
                ->body('new position successfully added')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($record?->exists) {
                $record->delete();
            }


            report($th);

            Notification::make()
                ->title('Ops')
                ->body('an error occurred while creating a new position')
                ->danger()
                ->send();

            $this->creationFailed = true;
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
