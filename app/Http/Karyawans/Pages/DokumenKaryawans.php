<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;

class DokumenKaryawans extends ManageRelatedRecords
{
    protected static string $resource = KaryawanResource::class;

    protected static string $relationship = 'dokumen';

    protected static ?string $relatedResource = KaryawanResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
