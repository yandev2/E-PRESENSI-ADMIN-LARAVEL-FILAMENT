<?php

namespace App\Filament\User\Resources\Jabatans\Schemas;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class JabatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::jabatanSection(),
                static::gajiSection(),
            ]);
    }

    public static function jabatanSection()
    {
        return  Section::make('Jabatan')
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 3,
                '2xl' => 3
            ])
            ->schema([
                TextEntry::make('kode_jabatan')
                    ->badge()
                    ->color('info')
                    ->label('Kode'),
                TextEntry::make('nama_jabatan')
                    ->label('Nama'),
                TextEntry::make('karyawan')
                    ->color('info')
                    ->badge()
                    ->label('Jumlah karyawan')
                    ->icon(Heroicon::UserGroup)
                    ->getStateusing(function ($record) {
                        return $record->karyawan?->count() ?? 0 . ' karyawan';
                    })
                    ->url(function ($record) {
                        return $record->karyawan->count() < 1 ? null : KaryawanResource::getUrl() . '?' . http_build_query([
                            'filters' => [
                                'jabatan' => [
                                    'value' => $record->nama_jabatan
                                ],
                            ],
                        ]);
                    }),
                TextEntry::make('deskripsi')
                    ->columnSpanFull()
                    ->placeholder('-'),
            ]);
    }

    public static function gajiSection()
    {
        return Section::make('Gaji')
            ->columns(1)
            ->schema([
                Grid::make(
                    [
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 5,
                        '2xl' => 5
                    ]
                )
                    ->schema([
                        TextEntry::make('gajiAktif.potongan_alpha')
                            ->label('Alpha')
                            ->color('danger')
                            ->suffix('%')
                            ->placeholder('-')
                            ->badge(),
                        TextEntry::make('gajiAktif.potongan_izin')
                            ->label('Izin')
                            ->color('info')
                            ->suffix('%')
                            ->placeholder('-')
                            ->badge(),
                        TextEntry::make('gajiAktif.potongan_sakit')
                            ->label('Sakit')
                            ->color('warning')
                            ->suffix('%')
                            ->placeholder('-')
                            ->badge(),
                        TextEntry::make('gajiAktif.potongan_tidak_absen_keluar')
                            ->label('Tidak Absen keluar')
                            ->color('success')
                            ->suffix('%')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2,
                                '2xl' => 2
                            ])
                            ->placeholder('-')
                            ->badge(),
                    ]),

                Grid::make(
                    [
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 3,
                        '2xl' => 3
                    ]
                )
                    ->schema([
                        TextEntry::make('gajiAktif.gaji_bulanan')
                            ->label('Gaji bulanan')
                            ->badge()
                            ->color('info')
                            ->placeholder('-')
                            ->prefix('Rp.'),
                        TextEntry::make('gajiAktif.gaji_lembur')
                            ->label('Gaji lembur')
                            ->badge()
                            ->color('info')
                            ->placeholder('-')
                            ->prefix('Rp.'),
                        TextEntry::make('gajiAktif.gaji_harian')
                            ->label('Gaji Harian')
                            ->badge()
                            ->color('info')
                            ->placeholder('-')
                            ->prefix('Rp.')
                    ]),
            ]);
    }
}
