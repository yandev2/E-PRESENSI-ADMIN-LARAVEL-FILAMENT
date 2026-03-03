<?php

namespace App\Filament\User\Resources\Karyawans\Tables;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;

class KaryawanColumn
{
    public static function configure(): array
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('karyawan.nik')
                ->label('Nik')
                ->icon(Heroicon::UserCircle)
                ->searchable()
                ->badge()
                ->color('info'),
            TextColumn::make('name')
                ->label('Nama')
                ->icon(Heroicon::User)
                ->searchable(),
            TextColumn::make('karyawan.nomor_telp')
                ->label('Kontak')
                ->icon(Heroicon::Phone)
                ->searchable()
                ->placeholder('-'),
            TextColumn::make('karyawan.jabatan.nama_jabatan')
                ->label('Jabatan')
                ->icon(Heroicon::Briefcase)
                ->placeholder('-')
                ->searchable(),
            TextColumn::make('karyawan.shift.nama_shift')
                ->label('Shift')
                ->icon(Heroicon::Calendar)
                ->searchable()
                ->placeholder('-'),

        ];
    }
}
