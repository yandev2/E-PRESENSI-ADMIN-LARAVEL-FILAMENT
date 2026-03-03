<?php

namespace App\Filament\App\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ShiftsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('')
                    ->schema([
                        TextInput::make('nama')
                            ->required(),
                        TimePicker::make('jam_masuk')
                            ->prefixIcon(Heroicon::Clock)
                            ->native(false)
                            ->reactive()
                            ->required(),
                        TimePicker::make('jam_keluar')
                            ->prefixIcon(Heroicon::Clock)
                            ->native(false)
                            ->after(fn($get) => $get('jam_masuk'))
                            ->required(),
                        Textarea::make('deskripsi')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull()
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
