<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendancesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('shift_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('jam_masuk')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('jam_pulang')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('lokasi_masuk')
                    ->placeholder('-'),
                TextEntry::make('lokasi_keluar')
                    ->placeholder('-'),
                TextEntry::make('wajah_masuk')
                    ->placeholder('-'),
                TextEntry::make('wajah_keluar')
                    ->placeholder('-'),
                TextEntry::make('durasi_kerja')
                    ->time()
                    ->placeholder('-'),
                IconEntry::make('lembur')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
