<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttendancesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('shift_id')
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('hadir'),
                TimePicker::make('jam_masuk'),
                TimePicker::make('jam_pulang'),
                TextInput::make('lokasi_masuk'),
                TextInput::make('lokasi_keluar'),
                TextInput::make('wajah_masuk'),
                TextInput::make('wajah_keluar'),
                TimePicker::make('durasi_kerja'),
                Toggle::make('lembur')
                    ->required(),
            ]);
    }
}
