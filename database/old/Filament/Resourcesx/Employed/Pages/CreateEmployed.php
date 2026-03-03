<?php

namespace App\Filament\Resources\Employed\Pages;

use App\Filament\Resources\Employed\EmployedResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployed extends CreateRecord
{
    protected static string $resource = EmployedResource::class;
    public function getTitle(): string
    {
        return 'Add New Employee';
    }
}
