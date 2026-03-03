<?php

namespace App\Filament\App\Resources\Users\Schemas;

use App\Filament\App\Resources\Positions\PositionsResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('userDetail.nik')
                            ->label('NIK')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.jenis_kelamin')
                            ->label('Jenis kelamin')
                            ->placeholder('-')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'P'   => 'Perempuan',
                                'L' => 'Laki Laki',
                            }),
                        TextEntry::make('userDetail.tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.tempat_lahir')
                            ->label('Tempat lahir')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.agama')
                            ->label('Agama')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.status_pernikahan')
                            ->label('Status pernikahan')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.alamat_ktp')
                            ->label('Alamat ktp')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.alamat_domisili')
                            ->label('Alamat domisili')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.tanggal_masuk')
                            ->label('Tanggal bergabung')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.tanggal_keluar')
                            ->label('Tanggal keluar')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.status_karyawan')
                            ->label('Status karyawan')
                            ->placeholder('-'),
                        TextEntry::make('userDetail.status')
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
                                        $jabatan = $record->userDetail->position ?? null;
                                        return $jabatan
                                            ? PositionsResource::getUrl('view', ['record' => $jabatan['id']])
                                            : null;
                                    }),
                            ])
                            ->schema([
                                TextEntry::make('userDetail.position.nama')
                                    ->label('Jabatan')
                                    ->placeholder('-'),
                                TextEntry::make('userDetail.position.golongan')
                                    ->label('Golongan')
                                    ->placeholder('-'),
                                TextEntry::make('userDetail.position.activeSalaries.hari_kerja')
                                    ->label('Jumlah hari kerja')
                                    ->suffix(' Hari')
                                    ->placeholder('-'),
                                TextEntry::make('userDetail.position.activeSalaries.gaji_bulanan')
                                    ->label('Gaji')
                                    ->prefix('Rp.')
                                    ->placeholder('-'),
                                TextEntry::make('userDetail.position.deskripsi')
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
