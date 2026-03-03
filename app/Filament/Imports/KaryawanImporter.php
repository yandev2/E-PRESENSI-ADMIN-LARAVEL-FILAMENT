<?php

namespace App\Filament\Imports;

use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class KaryawanImporter extends Importer
{
    protected static ?string $model =   User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->example('Nata S.Kom')
                ->requiredMapping(),
            ImportColumn::make('email')
                ->example('nata@gmail.com')
                ->requiredMapping(),

        ];
    }

    public function resolveRecord(): User
    {
        $data = $this->data;
        $karyawanKey = [
            'jabatan',
            'shift',
            'nik',
            'nip',
            'pendidikan_terakhir',
            'agama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'status_pernikahan',
            'alamat_ktp',
            'alamat_domisili',
            'tipe_karyawan',
            'status_karyawan',
            'nomor_telp',
            'status_dinas'
        ];

        $karyawanData = [];


        foreach ($karyawanKey as $key) {
            if (! isset($data[$key])) {
                continue;
            }

            $value = is_string($data[$key])
                ? strtolower($data[$key])
                : $data[$key];

            $karyawanData[$key] = match ($key) {
                'jenis_kelamin'    => strtolower(trim($value)) ?: 'l',
                'status_karyawan' => trim($value) ?: 'aktif',
                'tipe_karyawan'   => trim($value) ?: 'tetap',
                'status_dinas'    => trim($value) ?: 'dalam',
                default           => trim($value),
            };

            unset($data[$key]);
        }

        $karyawanData['shift_id'] = optional(
            Shift::where('nama_shift', 'ILIKE', trim($karyawanData['shift']))
                ->where('perusahaan_id', $this->options['perusahaan_id'])
                ->first()
        )->id;

        $karyawanData['jabatan_id'] = optional(
            Jabatan::where('nama_jabatan', 'ILIKE', trim($karyawanData['jabatan']))
                ->where('perusahaan_id', $this->options['perusahaan_id'])
                ->first()
        )->id;

        $userData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => '12345',
            'avatar' => $karyawanData['jenis_kelamin'] == 'l' ? 'karyawan/default_sys_l.jpg' : 'karyawan/default_sys.jpg',
            'perusahaan_id' => $this->options['perusahaan_id'],
        ];

        $users =  User::firstOrCreate(['email' => $userData['email']], $userData);
        $users->karyawan()->updateOrCreate(['user_id' => $users->id], $karyawanData);
        $users->assignRole('karyawan');
        return $users;
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your karyawan import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
