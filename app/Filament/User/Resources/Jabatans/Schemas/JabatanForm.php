<?php

namespace App\Filament\User\Resources\Jabatans\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::jabatanSection(),
                static::gajiSection()
            ]);
    }

    public static function jabatanSection(): Section
    {
        return Section::make('Jabatan')
            ->extraAttributes(['class' => 'form-section-custom'])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 2
            ])
            ->schema([
                TextInput::make('kode_jabatan')
                    ->label('kode')
                    ->required(),
                TextInput::make('nama_jabatan')
                    ->prefixIcon(Heroicon::Briefcase)
                    ->label('Nama')
                    ->required(),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function gajiSection(): Repeater
    {
        return  Repeater::make('gaji')
            ->hiddenLabel()
            ->relationship('gaji')
            ->schema([
                TextInput::make('gaji_bulanan')
                    ->required()
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $toInt = fn($value) => (int) str_replace([',', '.'], '', $value);
                        $hariKerja = (int) $get('jumlah_hari_kerja');
                        if ($state && $hariKerja > 0) {
                            $gajiBulanan = $toInt($state);
                            $gajiHarian  = round($gajiBulanan / $hariKerja);
                            $set('gaji_harian',  number_format(round($gajiHarian), 0, ',', '.'));
                        }
                    }),
                TextInput::make('gaji_harian')
                    ->required()
                    ->prefix('Rp.')
                    ->disabled()
                    ->dehydrated()
                    ->mask(RawJs::make('$money($input)')),
                TextInput::make('gaji_lembur')
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)')),
                TextInput::make('potongan_sakit')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_izin')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_alpha')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_tidak_absen_keluar')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('jumlah_hari_kerja')
                    ->label('Jumlah hari kerja')
                    ->numeric()
                    ->reactive()
                    ->suffix('H')
                    ->default(26)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $toInt = fn($value) => (int) str_replace([',', '.'], '', $value);
                        $hariKerja = (int) $state;

                        if ($state && $hariKerja > 0) {
                            $gajiBulanan = $toInt($get('gaji_bulanan'));
                            $gajiHarian  = round($gajiBulanan / $hariKerja);

                            $set('gaji_harian',  number_format(round($gajiHarian), 0, ',', '.'));
                        }
                    }),
                Hidden::make('berlaku_dari')
                    ->default(now()),
                Hidden::make('status')
                    ->required()
                    ->default('aktif'),

            ])
            ->grid(1)
            ->addable(false)
            ->deletable(false)
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 2,
                'xl' => 3,
                '2xl' => 3
            ])->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord);
    }
}
