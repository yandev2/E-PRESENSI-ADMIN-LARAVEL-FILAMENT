<?php

namespace App\Filament\User\Resources\Shifts\Schemas;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ShiftInfolist
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
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 4
            ])
            ->columnSpanFull()
            ->schema([
                TextEntry::make('nama_shift')
                    ->label('Shift'),
                TextEntry::make('karyawan')
                    ->color('info')
                    ->badge()
                    ->label('Jumlah karyawan')
                    ->icon(Heroicon::UserGroup)
                    ->getStateusing(function ($record) {
                        return $record->karyawan?->count() ?? 0 . ' karyawan';
                    })
                    ->url(function ($record) {
                        return $record->karyawan->count() < 1 ? null : KaryawanResource::getUrl() . '?' . http_build_query([
                            'filters' => [
                                'shift' => [
                                    'value' => $record->nama_shift
                                ],
                            ],
                        ]);
                    }),
                TextEntry::make('jam_masuk')
                    ->color('success')
                    ->badge()
                    ->icon(Heroicon::Clock),
                TextEntry::make('jam_keluar')
                    ->color('warning')
                    ->badge()
                    ->icon(Heroicon::Clock),
            ]);
    }
}
