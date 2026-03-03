<?php

namespace App\Filament\User\Resources\Presensis\Schemas;

use App\Filament\User\Componen\Button\DownloadButtonComponen;
use App\Filament\User\Resources\Jabatans\JabatanResource;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Filament\User\Resources\Shifts\ShiftResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class PresensiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Grid::make(1)
                    ->schema([
                        static::karyawaninfoSection(),
                        static::presensiInfoSection(),
                    ]),
                static::izinSection()
            ]);
    }

    public static function presensiInfoSection()
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->heading('Informasi Kehadiran')
            ->icon(Heroicon::FingerPrint)
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 3
            ])
            ->schema([
                TextEntry::make('status_masuk')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(fn($record) => $record->status_masuk ?? 'A'),
                TextEntry::make('jam_masuk')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::Clock)
                    ->getStateUsing(fn($record) => $record->jam_masuk ?? '-'),
                TextEntry::make('lokasi_masuk')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::MapPin)
                    ->getStateUsing(fn($record) => $record->lokasi_masuk ?? '-'),

                TextEntry::make('status_keluar')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(fn($record) => $record->status_keluar ?? 'A'),
                TextEntry::make('jam_keluar')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::Clock)
                    ->getStateUsing(fn($record) => $record->jam_keluar ?? '-'),
                TextEntry::make('lokasi_keluar')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::MapPin)
                    ->getStateUsing(fn($record) => $record->lokasi_keluar ?? '-'),

                TextEntry::make('shift.nama_shift')
                    ->badge()
                    ->color('info')
                    ->label('Shift')
                    ->getStateUsing(fn($record) => $record->shift?->nama_shift ?? '-')
                    ->url(function ($record) {
                        return $record->shift == null ? null : ShiftResource::getUrl('view', ['record' => $record->shift?->id]);
                    }),
                TextEntry::make('jabatan.nama_jabatan')
                    ->badge()
                    ->color('info')
                    ->label('Jabatan')
                    ->getStateUsing(fn($record) => $record->jabatan?->nama_jabatan ?? '-')
                    ->url(function ($record) {
                        return $record->jabatan == null ? null : JabatanResource::getUrl('view', ['record' => $record->jabatan?->id]);
                    }),
                TextEntry::make('gaji.gaji_harian')
                    ->badge()
                    ->color('info')
                    ->prefix('Rp.')
                    ->getStateUsing(fn($record) => $record->gaji?->gaji_harian ?? '-'),

                Grid::make(2)
                    ->columnSpanFull()
                    ->schema([
                        ImageEntry::make('wajah_masuk')
                            ->label('Wajah Masuk')
                            ->alignCenter()
                            ->imageSize('100%')
                            ->disk('public'),
                        ImageEntry::make('wajah_keluar')
                            ->label('Wajah Keluar')
                            ->alignCenter()
                            ->imageSize('100%')
                            ->disk('public'),
                    ]),
            ]);
    }
    public static function karyawaninfoSection()
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->icon(Heroicon::UserCircle)
            ->heading('Karyawan')
            ->afterHeader([
                Action::make('info_karyawan')
                    ->label('Lihat')
                    ->color('info')
                    ->icon(Heroicon::Eye)
                    ->url(function ($record) {
                        return $record->whereHas('karyawan') ?  KaryawanResource::getUrl('view', ['record' => $record?->karyawan?->id]) : null;
                    })
            ])
            ->schema([
                Grid::make([
                    'sm' => 1,
                    'md' => 1,
                    'lg' => 1,
                    'xl' => 3,
                    '2xl' => 3
                ])
                    ->schema([
                        ImageEntry::make('karyawan.user.avatar')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->imageSize('100%')
                            ->disk('public'),
                        Grid::make()
                            ->columnSpan(2)
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2
                            ])
                            ->schema([
                                TextEntry::make('karyawan.user.name')
                                    ->color('info')
                                    ->badge()
                                    ->label('Nama')
                                    ->placeholder('-')
                                    ->icon(Heroicon::User),
                                TextEntry::make('karyawan.nip')
                                    ->color('info')
                                    ->badge()
                                    ->label('NIP')
                                    ->placeholder('-')
                                    ->icon(Heroicon::FingerPrint),
                                TextEntry::make('karyawan.tipe_karyawan')
                                    ->color('info')
                                    ->badge()
                                    ->label('Tipe')
                                    ->placeholder('-'),
                                TextEntry::make('karyawan.status_dinas')
                                    ->color('info')
                                    ->badge()
                                    ->label('Status Dinas')
                                    ->placeholder('-'),
                                TextEntry::make('karyawan.jenis_kelamin')
                                    ->color('info')
                                    ->badge()
                                    ->label('Jenis Kelamin')
                                    ->placeholder('-'),
                                TextEntry::make('karyawan.pendidikan_terakhir')
                                    ->color('info')
                                    ->badge()
                                    ->label('Pendidikan')
                                    ->placeholder('-')
                            ])
                    ]),
            ]);
    }
    public static function izinSection()
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->heading('Informasi Izin')
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 2
            ])
            ->schema([
                ImageEntry::make('izin.file')
                    ->hiddenLabel()
                    ->alignCenter()
                    ->openUrlInNewTab()
                    ->url(fn($state) => $state ? Storage::url($state) : null)
                    ->imageSize('50%')
                    ->disk('public'),
                TextEntry::make('izin.izin')
                    ->getStateUsing(fn($record) => $record->izin?->izin ?? '-'),

                TextEntry::make('izin.deskripsi')
                    ->columnSpanFull()
                    ->getStateUsing(fn($record) => $record->izin?->deskripsi ?? '-'),
            ])
            ->hidden(fn($record) => $record->izin === null);
    }
}
