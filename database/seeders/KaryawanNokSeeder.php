<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KaryawanNokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $hubunganList = ['ayah', 'ibu', 'istri', 'anak', 'kerabat', 'kakek', 'nenek'];

        for ($karyawanId = 11; $karyawanId <= 20; $karyawanId++) {
            $jumlahNok = rand(1, 3); // tiap karyawan punya 1-3 NOK
            for ($i = 0; $i < $jumlahNok; $i++) {
                DB::table('nok_karyawans')->insert([
                    'perusahaan_id' => 8,
                    'karyawan_id' => $karyawanId,
                    'nama' => 'Nama ' . Str::random(5),
                    'hubungan' => $hubunganList[array_rand($hubunganList)],
                    'kontak' => '0812' . rand(10000000, 99999999),
                    'alamat' => 'Alamat ' . Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
