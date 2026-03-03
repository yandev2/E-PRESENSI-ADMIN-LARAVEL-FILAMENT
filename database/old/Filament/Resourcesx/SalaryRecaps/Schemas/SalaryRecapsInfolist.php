<?php

namespace App\Filament\Resources\SalaryRecaps\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SalaryRecapsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('position_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bulan')
                    ->numeric(),
                TextEntry::make('tahun')
                    ->numeric(),
                TextEntry::make('total_hadir')
                    ->numeric(),
                TextEntry::make('total_alpha')
                    ->numeric(),
                TextEntry::make('total_izin')
                    ->numeric(),
                TextEntry::make('gaji_bulanan')
                    ->numeric(),
                TextEntry::make('total_potongan')
                    ->numeric(),
                TextEntry::make('gaji_diterima')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('file')
                    ->placeholder('-'),
                TextEntry::make('generate_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
