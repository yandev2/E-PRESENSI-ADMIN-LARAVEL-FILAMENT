<?php

namespace App\Filament\User\Resources\TidakHadirs\Pages;

use App\Filament\User\Resources\TidakHadirs\TidakHadirResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListTidakHadirs extends ListRecords
{
    protected static string $resource = TidakHadirResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Karyawan Tidak Hadir';
    }
}
