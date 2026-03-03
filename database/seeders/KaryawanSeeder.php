<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds. php artisan db:seed --class=PresensiSeeder

     */
    public function run(): void
    {
        $jabatanIds = [2, 3, 4, 5, 6, 7, 8];
        $shiftIds   = [null, 3, 4, 5];
        $agama      = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'];
        $gender     = ['L', 'P'];
        $statusNikah = ['lajang', 'menikah'];

        for ($i = 1; $i <= 10; $i++) {

            // ======================
            // USER
            // ======================
            $user = User::create([
                'perusahaan_id' => 8,
                'name'          => 'Karyawan ' . $i,
                'email'         => 'karyawan' . $i . '@perusahaan.com',
                'password'      => Hash::make('password'),
                'is_owner'      => false,
            ]);

            // ======================
            // KARYAWAN
            // ======================
            Karyawan::create([
                'perusahaan_id'      => 8,
                'user_id'            => $user->id,
                'jabatan_id'         => $jabatanIds[array_rand($jabatanIds)],
                'shift_id'           => $shiftIds[array_rand($shiftIds)],
                'nik'                => '3208' . rand(10000000, 99999999),
                'nama'               => $user->name,
                'agama'              => $agama[array_rand($agama)],
                'tempat_lahir'       => 'Jakarta',
                'nomor_telp'        => '08' .  rand(10000000, 99999999),
                'email'              => 'karyawan' . $i . '@gmail.com',
                'tanggal_lahir'      => now()->subYears(rand(20, 40))->subDays(rand(1, 365)),
                'jenis_kelamin'      => $gender[array_rand($gender)],
                'status_pernikahan'  => $statusNikah[array_rand($statusNikah)],
                'alamat_ktp'         => 'Jl. Contoh Alamat KTP No. ' . rand(1, 200),
                'alamat_domisili'    => 'Jl. Domisili No. ' . rand(1, 200),
                'tipe_karyawan'      => 'tetap',
                'status_karyawan'    => 'aktif',
            ]);
        }
    }
}
