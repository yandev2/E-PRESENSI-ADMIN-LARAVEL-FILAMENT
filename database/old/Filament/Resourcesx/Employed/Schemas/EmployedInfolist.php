<?php

namespace App\Filament\Resources\Employed\Schemas;

use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EmployedInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Employred Details')
                            ->icon(Heroicon::User)
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2
                            ])
                            ->schema([
                                ImageEntry::make('avatar')
                                    ->hiddenLabel()
                                    ->alignCenter()
                                    ->imageSize('100%')
                                    ->disk('public'),
                                Grid::make([
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                    'xl' => 2
                                ])
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Nama'),
                                        TextEntry::make('email')
                                            ->label('Email address'),
                                        TextEntry::make('userDetail.tanggal_masuk')
                                            ->date('d M Y')
                                            ->label('Tanggal Masuk'),
                                        TextEntry::make('userDetail.tanggal_keluar')
                                            ->date('d M Y')
                                            ->label('Tanggal Keluar'),
                                        TextEntry::make('userDetail.nik')
                                            ->label('NIK'),
                                        TextEntry::make('userDetail.status_karyawan')
                                            ->label('Tipe Karyawan'),
                                        TextEntry::make('userDetail.alamat_ktp')
                                            ->columnSpanFull()
                                            ->label('Alamat KTP'),
                                        TextEntry::make('userDetail.alamat_domisili')
                                            ->columnSpanFull()
                                            ->label('Alamat Domisili'),
                                        Fieldset::make('')
                                            ->columnSpanFull()
                                            ->columns([
                                                'sm' => 2,
                                                'md' => 1,
                                                'lg' => 1,
                                                'xl' => 2
                                            ])
                                            ->schema([
                                                TextEntry::make('userDetail.position.nama')
                                                    ->label('Jabatan')
                                                    ->getStateUsing(function ($record) {
                                                        if ($record->userDetail->position) {
                                                            return 'Posisi ' . $record->userDetail->position->nama . ' Golongan ' . $record->userDetail->position->golongan;
                                                        }
                                                        return 'Belum Ada Posisi';
                                                    }),
                                                TextEntry::make('userDetail.shift.nama')
                                                    ->label('Shift Kerja')
                                                    ->getStateUsing(function ($record) {
                                                        if ($record->userDetail->shift) {
                                                            return 'Posisi ' . $record->userDetail->shift->nama;
                                                        }
                                                        return 'Belum Ada Shift';
                                                    }),
                                                TextEntry::make('userDetail.position.nama')
                                                    ->label('Gaji')
                                                    ->getStateUsing(function ($record) {
                                                        if ($record->userDetail->position) {
                                                            return 'Gaji Bulanan Rp.' . $record->position->activeSalaries->gaji_bulanan . '\n Gaji Lembur Rp.' . $record->position->activeSalaries->gaji_lembur;
                                                        }
                                                        return 'Belum Ada Gaji';
                                                    }),
                                                TextEntry::make('userDetail.shift.nama')
                                                    ->label('Jam Kerja')
                                                    ->getStateUsing(function ($record) {
                                                        if ($record->userDetail->shift) {
                                                            return 'Jam Masuk ' . $record->userDetail->shift->jam_masuk . ' - Jam Keluar ' . $record->userDetail->shift->jam_keluar;
                                                        }
                                                        return 'Belum Ada Jadwal Kerja';
                                                    }),
                                            ])
                                    ]),
                            ]),
                        Tab::make('Other Details')
                            ->icon(Heroicon::Identification)
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 3,
                            ])
                            ->schema([
                                TextEntry::make('userDetail.tempat_lahir')
                                    ->label('Tempat Lahir'),
                                TextEntry::make('userDetail.tanggal_lahir')
                                    ->date('d M Y')
                                    ->label('Tanggal Lahir'),
                                TextEntry::make('userDetail.nomor_ponsel')
                                    ->label('Nomor Ponsel'),
                                TextEntry::make('userDetail.agama')
                                    ->label('Agama'),
                                TextEntry::make('userDetail.status_pernikahan')
                                    ->label('Status Pernikahan'),
                                TextEntry::make('userDetail.jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn($state) => $state === 'l' ? 'Laki-laki' : 'Perempuan'),
                            ]),
                        Tab::make('Next Of Kin')
                            ->icon(Heroicon::Users)
                            ->columnSpanFull()
                            ->schema([
                                RepeatableEntry::make('userNok')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('nama'),
                                        TextEntry::make('hubungan'),
                                        TextEntry::make('kontak'),
                                    ])
                                    ->grid(2)
                                    ->columns([
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                        'xl' => 3,
                                    ])
                            ]),
                    ]),

            ]);
    }
}
