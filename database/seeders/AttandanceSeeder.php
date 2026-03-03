<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttandanceSeeder extends Seeder
{
  public function run(): void
    {
        $userId    = 25;
        $companyId = 20;
        $shiftId   = 1;

        $startDate = Carbon::create(2026, 2, 1);
        $endDate   = Carbon::create(2026, 2, 28);

        $statuses = ['H'];

        // Tentukan 2 hari lembur (random)
        $lemburDays = collect(range(1, 28))->random(2)->values();

        foreach (range(1, 28) as $day) {

            $tanggal = Carbon::create(2026, 2, $day);
            $status  = collect($statuses)->random();

            $isHadir = $status === 'H';

            DB::table('attendances')->insert([
                'company_id'   => $companyId,
                'user_id'      => $userId,
                'shift_id'     => $shiftId,
                'tanggal'      => $tanggal->toDateString(),
                'status'       => 'H',

                // Jam hanya diisi jika hadir
                'jam_masuk'    => $isHadir ? '08:00:00' : null,
                'jam_pulang'   => $isHadir ? '12:00:00' : null,
                'durasi_kerja' => $isHadir ? '04:00:00' : null,

                // Lembur hanya untuk 2 hari & harus hadir
                'lembur'       => false,

                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
