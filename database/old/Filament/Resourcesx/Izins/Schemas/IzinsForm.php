<?php

namespace App\Filament\Resources\Izins\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IzinsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('approved_by')
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('file'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
