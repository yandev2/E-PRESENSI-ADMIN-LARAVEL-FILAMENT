<?php

namespace App\Filament\App\Resources\Positions\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PositionsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Position")
                    ->schema([
                        TextEntry::make('nama'),
                        TextEntry::make('kode'),
                        TextEntry::make('golongan')
                            ->placeholder('-'),
                        TextEntry::make('jumlah_karyawan')
                            ->getStateUsing(function ($record) {
                                $user = auth()->user()->id;
                                return $record->karyawan->count();
                            }),
                        TextEntry::make('deskripsi')
                            ->placeholder('-')
                            ->columnSpan(2),

                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3
                    ]),

                Section::make("Salary")
                    ->schema([
                        TextEntry::make('activeSalaries.gaji_bulanan')
                            ->prefix('Rp.'),
                        TextEntry::make('activeSalaries.gaji_lembur')
                            ->prefix('Rp.'),
                        TextEntry::make('activeSalaries.hari_kerja')
                            ->suffix(' H'),
                        TextEntry::make('activeSalaries.effective_from')
                            ->label('Aktif dari'),
                        TextEntry::make('activeSalaries.effective_to')
                            ->label('Aktif sampai')
                            ->default(Carbon::now()->format('d-M-Y')),
                    ])
                      ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 3
                    ]),

                Section::make("Potongan Gaji")
                    ->schema([
                        TextEntry::make('activeSalaries.potongan_alpha_persen')
                            ->label('Alpha')
                            ->color('danger')
                            ->suffix('%')
                            ->badge(),
                        TextEntry::make('activeSalaries.potongan_izin_persen')
                            ->label('Izin')
                            ->color('info')
                            ->suffix('%')
                            ->badge(),
                        TextEntry::make('activeSalaries.potongan_sakit_persen')
                            ->label('Sakit')
                            ->color('warning')
                            ->suffix('%')
                            ->badge(),
                    ])
                     ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3
                    ]),







            ]);
    }
}
