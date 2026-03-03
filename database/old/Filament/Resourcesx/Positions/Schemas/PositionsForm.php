<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PositionsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 3,
                        'xl' => 3
                    ])
                    ->schema([
                        Select::make('golongan')
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('')
                            ->required()
                            ->options([
                                'Pimpinan' => '1. Pimpinan',
                                'Manajerial' => '2. Manajerial',
                                'Supervisor' => '3. Supervisor',
                                'Operasional' => '4. Operasional',
                                'Other' => '5. Other',
                            ]),
                        TextInput::make('kode')
                            ->required(),
                        TextInput::make('nama')
                            ->required(),
                        Textarea::make('deskripsi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
