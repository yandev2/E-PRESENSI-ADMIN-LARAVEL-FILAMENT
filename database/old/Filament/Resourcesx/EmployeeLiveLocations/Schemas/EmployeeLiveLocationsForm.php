<?php

namespace App\Filament\Resources\EmployeeLiveLocations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeLiveLocationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('attendance_id')
                    ->required()
                    ->numeric(),
                TextInput::make('latitude')
                    ->required(),
                TextInput::make('longitude')
                    ->required(),
                Textarea::make('accuracy')
                    ->columnSpanFull(),
                DatePicker::make('last_update_at'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
