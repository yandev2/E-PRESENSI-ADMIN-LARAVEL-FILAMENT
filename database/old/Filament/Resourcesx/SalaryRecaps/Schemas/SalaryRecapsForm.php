<?php

namespace App\Filament\Resources\SalaryRecaps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalaryRecapsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('position_id')
                    ->numeric(),
                TextInput::make('bulan')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('total_hadir')
                    ->required()
                    ->numeric(),
                TextInput::make('total_alpha')
                    ->required()
                    ->numeric(),
                TextInput::make('total_izin')
                    ->required()
                    ->numeric(),
                TextInput::make('gaji_bulanan')
                    ->required()
                    ->numeric(),
                TextInput::make('total_potongan')
                    ->required()
                    ->numeric(),
                TextInput::make('gaji_diterima')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('diterima'),
                TextInput::make('file'),
                DatePicker::make('generate_at'),
            ]);
    }
}
