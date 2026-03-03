<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::table('izins')->insert([
            'perusahaan_id' => 8,
            'karyawan_id' => 107,
            'presensi_id' => 181,
            'izin' => 'Sakit',
            'deskripsi' => 'saya sakit tidak enak badan',
            'file' => '',
        ]);
    }
}
