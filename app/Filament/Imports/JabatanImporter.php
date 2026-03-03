<?php

namespace App\Filament\Imports;

use App\Models\Jabatan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class JabatanImporter extends Importer
{
    protected static ?string $model = Jabatan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_jabatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('nama_jabatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('deskripsi'),
        ];
    }

    public function resolveRecord(): Jabatan
    {
        return Jabatan::firstOrNew([
            'kode_jabatan' => $this->data['kode_jabatan'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your jabatan import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
