<?php

namespace App\Filament\App\Resources\Shifts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShiftsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('')
                    ->schema([
                        TextEntry::make('nama'),
                        TextEntry::make('jam_masuk')
                            ->time(),
                        TextEntry::make('jam_keluar')
                            ->time(),
                        TextEntry::make('deskripsi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 3,
                        '2xl' => 3
                    ])



            ]);
    }
}
