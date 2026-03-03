<?php

namespace App\Filament\User\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('perusahaan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('karyawan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('shift_id')
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                Textarea::make('status_masuk')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('status_keluar')
                    ->columnSpanFull(),
                TimePicker::make('jam_masuk'),
                TimePicker::make('jam_keluar'),
                TextInput::make('lokasi_masuk'),
                TextInput::make('lokasi_keluar'),
                TextInput::make('wajah_masuk'),
                TextInput::make('wajah_keluar'),
                Toggle::make('is_lembur')
                    ->required(),
                TextInput::make('file'),
                TextInput::make('jabatan_id')
                    ->numeric(),
                TextInput::make('gaji_id')
                    ->numeric(),
            ]);
    }
}
