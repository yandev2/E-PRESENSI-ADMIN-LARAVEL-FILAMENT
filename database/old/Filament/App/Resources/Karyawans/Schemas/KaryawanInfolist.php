<?php

namespace App\Filament\App\Resources\Karyawans\Schemas;

use App\Filament\App\Resources\Karyawans\KaryawanResource;
use App\Filament\App\Resources\Positions\PositionsResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class KaryawanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('karyawan')
                    ->description('Informasi karyawan')
                    ->afterHeader([
                        Action::make('edit data')
                            ->url(function ($record) {
                                return  KaryawanResource::getUrl('edit', ['record' => $record['id']]);
                            }),
                    ])
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama'),
                        TextEntry::make('user.email')
                            ->label('Email'),
                        TextEntry::make('nik')
                            ->label('NIK')
                            ->placeholder('-'),
                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis kelamin')
                            ->placeholder('-')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'P'   => 'Perempuan',
                                'L' => 'Laki Laki',
                            }),
                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->placeholder('-'),
                        TextEntry::make('tempat_lahir')
                            ->label('Tempat lahir')
                            ->placeholder('-'),
                        TextEntry::make('agama')
                            ->label('Agama')
                            ->placeholder('-'),
                        TextEntry::make('status_pernikahan')
                            ->label('Status pernikahan')
                            ->placeholder('-'),
                        TextEntry::make('alamat_ktp')
                            ->label('Alamat ktp')
                            ->placeholder('-'),
                        TextEntry::make('alamat_domisili')
                            ->label('Alamat domisili')
                            ->placeholder('-'),
                        TextEntry::make('tanggal_masuk')
                            ->label('Tanggal bergabung')
                            ->placeholder('-'),
                        TextEntry::make('tanggal_keluar')
                            ->label('Tanggal keluar')
                            ->placeholder('-'),
                        TextEntry::make('status_karyawan')
                            ->label('Status karyawan')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->placeholder('-'),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 3,
                        '2xl' => 2
                    ]),

                Grid::make()
                    ->schema([
                        Section::make('Jabatan')
                            ->description('Jabatan karyawan')
                            ->afterHeader([
                                Action::make('lihat_detail')
                                    ->url(function ($record) {
                                        $jabatan = $record->userPosition ?? null;
                                        return $jabatan
                                            ? PositionsResource::getUrl('view', ['record' => $jabatan['id']])
                                            : null;
                                    }),
                            ])
                            ->schema([
                                TextEntry::make('userPosition.nama')
                                    ->label('Jabatan')
                                    ->placeholder('-'),
                                TextEntry::make('userPosition.golongan')
                                    ->label('Golongan')
                                    ->placeholder('-'),
                                TextEntry::make('userPosition.activeSalaries.hari_kerja')
                                    ->label('Jumlah hari kerja')
                                    ->suffix(' Hari')
                                    ->placeholder('-'),
                                TextEntry::make('userPosition.activeSalaries.gaji_bulanan')
                                    ->label('Gaji')
                                    ->prefix('Rp.')
                                    ->placeholder('-'),
                                TextEntry::make('userPosition.deskripsi')
                                    ->label('Deskripsi')
                                    ->columnSpanFull()
                                    ->placeholder('-'),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 3,
                                '2xl' => 2
                            ]),

                        Section::make('Kehadiran dalam 1 bulan sekarang')
                            ->description(function ($record) {
                                $now = Carbon::now()->format('M-Y');
                                return $now;
                            })
                            ->schema([
                                TextEntry::make('hadir')
                                    ->label('Hadir')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('-'),
                                TextEntry::make('izin')
                                    ->label('Izin')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('-'),
                                TextEntry::make('sakit')
                                    ->label('Sakit')
                                    ->badge()
                                    ->color('warning')
                                    ->placeholder('-'),
                                TextEntry::make('alpha')
                                    ->label('Alpha')
                                    ->badge()
                                    ->color('danger')
                                    ->placeholder('-'),
                            ])
                            ->footer([
                                TextEntry::make('info')
                                    ->hiddenLabel()
                                    ->state(function ($record) {
                                        $toInt   = fn($v) => (int) str_replace([',', '.'], '', $v);
                                        $rupiah  = fn($v) => 'Rp ' . number_format(round($v), 0, ',', '.');

                                        $salary = $record->userPosition->activeSalaries;
                                        if (! $salary) return 0;

                                        $gajiBulanan   = $toInt($salary->gaji_bulanan);
                                        $gajiLemburJam = $toInt($salary->gaji_lembur);
                                        $hariWajib     = (int) $salary->hari_kerja;

                                        $potongan = [
                                            'A' => (float) $salary->potongan_alpha_persen,
                                            'S' => (float) $salary->potongan_sakit_persen,
                                            'I' => (float) $salary->potongan_izin_persen,
                                        ];

                                        $shift = $record->userShift;

                                        $jamShift = Carbon::createFromFormat('H:i:s', $shift->jam_masuk)
                                            ->diffInMinutes(Carbon::createFromFormat('H:i:s', $shift->jam_keluar)) / 60;

                                        $gajiHarian = $gajiBulanan / $hariWajib;
                                        $gajiPerJam = $gajiHarian / $jamShift;

                                        $presensi = $record->user->userAttendance()
                                            ->whereMonth('tanggal', now()->month)
                                            ->whereYear('tanggal', now()->year)
                                            ->get();

                                        $totalGajiPokok = 0;
                                        $totalPotongan  = 0;
                                        $totalLembur    = 0;

                                        foreach ($presensi as $p) {

                                            $jamKerja = 0;
                                            if ($p->durasi_kerja) {
                                                $jamKerja = Carbon::createFromTime(0, 0)
                                                    ->diffInMinutes(Carbon::createFromFormat('H:i:s', $p->durasi_kerja)) / 60;
                                            }

                                            if ($p->status === 'H' && $jamKerja > 0) {

                                                $gajiHariIni = min($jamKerja, $jamShift) * $gajiPerJam;
                                                $totalGajiPokok += $gajiHariIni;

                                                if ($p->lembur) {
                                                    $totalLembur += $jamKerja * $gajiLemburJam;
                                                }

                                                continue;
                                            }

                                            if (isset($potongan[$p->status])) {
                                                $totalPotongan += $gajiHarian * ($potongan[$p->status] / 100);
                                            }
                                        }

                                        $totalGajiDiterima = $totalGajiPokok - $totalPotongan + $totalLembur;

                                        return sprintf(
                                            "Gaji Pokok : %s | Potongan : %s | Lembur : %s | Gaji Diterima : %s",
                                            $rupiah($totalGajiPokok),
                                            $rupiah($totalPotongan),
                                            $rupiah($totalLembur),
                                            $rupiah($totalGajiDiterima),
                                        );
                                    })

                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 4,
                                '2xl' => 4
                            ]),

                    ])
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ]),


            ]);
    }
}
