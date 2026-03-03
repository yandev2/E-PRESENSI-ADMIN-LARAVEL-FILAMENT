<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class JabatanDanGajiITSeeder extends Seeder
{
 //php artisan db:seed --class=JabatanDanGajiITSeeder

    public function run(): void
    {
        $perusahaanId = 8;
        $now = now();

        $jabatans = [
            [
                'kode_jabatan' => 'IT-MGR',
                'nama_jabatan' => 'IT Manager',
                'deskripsi'    => 'Mengelola tim IT, arsitektur sistem, dan kebijakan teknologi.',
                'gaji' => [
                    'gaji_bulanan' => '12,000,000',
                    'gaji_harian'  => '428,571',
                    'gaji_lembur'  => '150,000',
                ],
            ],
            [
                'kode_jabatan' => 'BE-DEV',
                'nama_jabatan' => 'Backend Developer',
                'deskripsi'    => 'Mengembangkan API, database, dan logika bisnis.',
                'gaji' => [
                    'gaji_bulanan' => '8,000,000',
                    'gaji_harian'  => '285,714',
                    'gaji_lembur'  => '100,000',
                ],
            ],
            [
                'kode_jabatan' => 'FE-DEV',
                'nama_jabatan' => 'Frontend Developer',
                'deskripsi'    => 'Mengembangkan UI web dan integrasi frontend.',
                'gaji' => [
                    'gaji_bulanan' => '7,000,000',
                    'gaji_harian'  => '250,000',
                    'gaji_lembur'  => '90,000',
                ],
            ],
            [
                'kode_jabatan' => 'FL-DEV',
                'nama_jabatan' => 'Flutter Developer',
                'deskripsi'    => 'Mengembangkan aplikasi mobile Flutter.',
                'gaji' => [
                    'gaji_bulanan' => '7,500,000',
                    'gaji_harian'  => '267,857',
                    'gaji_lembur'  => '95,000',
                ],
            ],
            [
                'kode_jabatan' => 'QA-ENG',
                'nama_jabatan' => 'QA Engineer',
                'deskripsi'    => 'Pengujian sistem, automation test, dan quality control.',
                'gaji' => [
                    'gaji_bulanan' => '6,000,000',
                    'gaji_harian'  => '214,285',
                    'gaji_lembur'  => '80,000',
                ],
            ],
            [
                'kode_jabatan' => 'DEVOPS',
                'nama_jabatan' => 'DevOps Engineer',
                'deskripsi'    => 'CI/CD, server, deployment, dan monitoring.',
                'gaji' => [
                    'gaji_bulanan' => '9,000,000',
                    'gaji_harian'  => '321,428',
                    'gaji_lembur'  => '120,000',
                ],
            ],
            [
                'kode_jabatan' => 'IT-SUP',
                'nama_jabatan' => 'IT Support',
                'deskripsi'    => 'Support teknis, troubleshooting, dan maintenance.',
                'gaji' => [
                    'gaji_bulanan' => '5,000,000',
                    'gaji_harian'  => '178,571',
                    'gaji_lembur'  => '70,000',
                ],
            ],
        ];

        foreach ($jabatans as $jabatan) {

            $jabatanId = DB::table('jabatans')->insertGetId([
                'perusahaan_id' => $perusahaanId,
                'kode_jabatan'  => $jabatan['kode_jabatan'],
                'nama_jabatan'  => $jabatan['nama_jabatan'],
                'deskripsi'     => $jabatan['deskripsi'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            DB::table('gajis')->insert([
                'perusahaan_id'                 => $perusahaanId,
                'jabatan_id'                    => $jabatanId,
                'gaji_bulanan'                  => $jabatan['gaji']['gaji_bulanan'],
                'gaji_harian'                   => $jabatan['gaji']['gaji_harian'],
                'gaji_lembur'                   => $jabatan['gaji']['gaji_lembur'],
                'potongan_sakit'                => 5,
                'potongan_izin'                 => 2,
                'jumlah_hari_kerja'             => 26,
                'potongan_alpha'                => 10,
                'potongan_tidak_absen_keluar'   => 5,
                'berlaku_dari'                  => Carbon::now()->startOfYear(),
                'berlaku_sampai'                => null,
                'status'                        => 'aktif',
                'created_at'                    => $now,
                'updated_at'                    => $now,
            ]);
        }
    }
}
