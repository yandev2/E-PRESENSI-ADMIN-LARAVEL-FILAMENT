<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiveLocationKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('live_locationkaryawans')->insert([
            'perusahaan_id' => 8,
            'karyawan_id'   => 107,
            'presensi_id'   => 171,
            'latitude'      => '-3.2922065210891853',
            'longitude'     => '102.86829356162235',
            'address'       => 'Jawa Kanan Ss, Kec. Lubuk Linggau Tim. II, Kota Lubuklinggau',
            'is_active'     => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
