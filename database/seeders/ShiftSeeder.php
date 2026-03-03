<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shifts')->insert([
            [
                'perusahaan_id' => 8,
                'nama_shift'    => 'Shift Pagi',
                'jam_masuk'     => '08:00:00',
                'jam_keluar'    => '12:00:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'perusahaan_id' => 8,
                'nama_shift'    => 'Shift Malam',
                'jam_masuk'     => '19:00:00',
                'jam_keluar'    => '23:00:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
