<?php

namespace App\Filament\Resources\DailyReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DailyReportsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attendance_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('approved_by')
                    ->numeric(),
                TextInput::make('task_id')
                    ->numeric(),
                Textarea::make('laporan')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('file'),
                TextInput::make('latitude'),
                TextInput::make('longitude'),
                DatePicker::make('reported_at')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('catatan_alasan')
                    ->columnSpanFull(),
            ]);
    }
}
