<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Shift;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::create(2026, 2, 1);
        $end   = Carbon::create(2026, 2, 28);

        $shift = Shift::findOrFail(3);

        $statusList = ['H', 'I', 'S'];

        $statusKeluarNullCount = 0;
        $lemburSet = false;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {

            // Skip hari Minggu
            if ($date->isSunday()) {
                continue;
            }

            $statusMasuk = $statusList[array_rand($statusList)];

            $data = [
                'perusahaan_id' => 8,
                'karyawan_id'   => 107,
                'shift_id'      => 3,
                'jabatan_id'    => 12,
                'gaji_id'       => 25,
                'tanggal'       => $date->toDateString(),
                'status_masuk'  => $statusMasuk,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            // ❌ TIDAK HADIR
            if ($statusMasuk !== 'H') {
                $data += [
                    'status_keluar' => null,
                    'jam_masuk'     => null,
                    'jam_keluar'    => null,
                    'lokasi_masuk'  => null,
                    'lokasi_keluar' => null,
                    'wajah_masuk'   => null,
                    'wajah_keluar'  => null,
                    'is_lembur'     => false,
                ];
            } else {

                // ✅ HADIR
                $statusKeluar = 'H';

                if ($statusKeluarNullCount < 4) {
                    $statusKeluar = null;
                    $statusKeluarNullCount++;
                }

                $isLembur = false;
                if (! $lemburSet) {
                    $isLembur = true;
                    $lemburSet = true;
                }

                $data += [
                    'status_keluar' => $statusKeluar,
                    'jam_masuk'     => $shift->jam_masuk,
                    'lokasi_masuk'  => '-3.266803, 102.939558',
                    'wajah_masuk'   => null,
                    'wajah_keluar'  => null,
                    'is_lembur'     => $isLembur,
                ];

                // 🔑 LOGIKA BARU
                if ($statusKeluar === null) {
                    $data += [
                        'jam_keluar'    => null,
                        'lokasi_keluar' => null,
                    ];
                } else {
                    $data += [
                        'jam_keluar'    => $shift->jam_keluar,
                        'lokasi_keluar' => '-3.266803, 102.939558',
                    ];
                }
            }

            DB::table('presensis')->insert($data);
        }
    }
}
