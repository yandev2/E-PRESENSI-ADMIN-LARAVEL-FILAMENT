<?php

namespace App\Filament\User\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::shiftSection()
            ]);
    }

    public static function shiftSection()
    {
        return Section::make('Shift')
            ->extraAttributes(['class' => 'form-section-custom'])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 2
            ])
            ->schema([
                TextInput::make('nama_shift')
                    ->required()
                    ->columnSpanFull(),
                TimePicker::make('jam_masuk')
                    ->prefixIcon(Heroicon::Clock)
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->native(false)
                    ->required(),
                TimePicker::make('jam_keluar')
                    ->prefixIcon(Heroicon::Clock)
                    ->after(fn($get) => $get('jam_masuk'))
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->native(false)
                    ->required(),
            ]);
    }
}
