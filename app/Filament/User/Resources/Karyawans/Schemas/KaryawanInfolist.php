<?php

namespace App\Filament\User\Resources\Karyawans\Schemas;

use App\Filament\User\Resources\Jabatans\JabatanResource;
use App\Filament\User\Resources\Shifts\ShiftResource;
use Carbon\Carbon;
use EduardoRibeiroDev\FilamentLeaflet\Enums\TileLayer;
use EduardoRibeiroDev\FilamentLeaflet\Infolists\MapEntry;
use EduardoRibeiroDev\FilamentLeaflet\Support\Markers\Marker;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class KaryawanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::karyawanInfoSection(),
                Grid::make(1)
                    ->schema([
                        static::posisiKaryawanSection(),
                        static::kehadiranSection()
                    ]),

            ]);
    }

    public static function karyawanInfoSection(): Tabs
    {
        return Tabs::make('Karyawan')
            ->tabs([
                Tab::make('Karyawan')
                    ->icon(Heroicon::User)
                    ->columns(1)
                    ->schema([
                        Grid::make([
                            'sm' => 1,
                            'md' => 1,
                            'lg' => 1,
                            'xl' => 3,
                            '2xl' => 3
                        ])
                            ->schema([
                                ImageEntry::make('user.avatar')
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
                                        TextEntry::make('user.name')
                                            ->color('info')
                                            ->badge()
                                            ->label('Nama')
                                            ->placeholder('-')
                                            ->icon(Heroicon::User),
                                        TextEntry::make('nip')
                                            ->color('info')
                                            ->badge()
                                            ->label('NIP')
                                            ->placeholder('-')
                                            ->icon(Heroicon::FingerPrint),
                                        TextEntry::make('status_karyawan')
                                            ->color('info')
                                            ->badge()
                                            ->label('Status')
                                            ->placeholder('-'),
                                        TextEntry::make('tipe_karyawan')
                                            ->color('info')
                                            ->badge()
                                            ->label('Tipe')
                                            ->placeholder('-')
                                    ])
                            ]),


                        Grid::make()
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 3
                            ])
                            ->schema([
                                TextEntry::make('status_dinas')
                                    ->color('info')
                                    ->badge()
                                    ->label('Status Dinas')
                                    ->placeholder('-'),
                                TextEntry::make('pendidikan_terakhir')
                                    ->color('info')
                                    ->badge()
                                    ->label('Pendidikan')
                                    ->placeholder('-'),
                                TextEntry::make('usia')
                                    ->color('info')
                                    ->badge()
                                    ->suffix(' Tahun')
                                    ->label('Usia')
                                    ->placeholder('-'),
                            ])
                    ]),

                Tab::make('More')
                    ->icon(Heroicon::Identification)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                        '2xl' => 3
                    ])
                    ->schema([
                        TextEntry::make('nomor_telp')
                            ->color('info')
                            ->badge()
                            ->label('Kontak')
                            ->placeholder('-')
                            ->icon(Heroicon::Phone),
                        TextEntry::make('user.email')
                            ->color('info')
                            ->badge()
                            ->label('Email')
                            ->placeholder('-')
                            ->icon(Heroicon::Envelope),
                        TextEntry::make('agama')
                            ->color('info')
                            ->badge()
                            ->label('Agama')
                            ->placeholder('-'),
                        TextEntry::make('jenis_kelamin')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'l' => 'info',
                                'p' => 'danger'
                            })
                            ->formatStateUsing(fn($state) => match ($state) {
                                'l' => 'Laki Laki',
                                'p' => 'Perempuan'
                            }),
                        TextEntry::make('status_pernikahan')
                            ->color('info')
                            ->badge()
                            ->label('Status pernikahan')
                            ->placeholder('-'),
                        TextEntry::make('tanggal_lahir')
                            ->color('info')
                            ->badge()
                            ->date('d-M-Y')
                            ->label('Tanggal lahir')
                            ->placeholder('-')
                            ->icon(Heroicon::Calendar),
                        TextEntry::make('tempat_lahir')
                            ->color('info')
                            ->badge()
                            ->label('Tempat lahir')
                            ->placeholder('-')
                            ->icon(Heroicon::Map),
                        TextEntry::make('alamat_domisili')
                            ->placeholder('-')
                            ->label('Alamat domisili'),
                        TextEntry::make('alamat_ktp')
                            ->placeholder('-')
                            ->label('Alamat ktp')
                    ]),

                Tab::make('Face ID')
                    ->icon(Heroicon::User)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])
                    ->schema([
                        TextEntry::make('user.face_id')
                            ->color('info')
                            ->badge()
                            ->label('Face id')
                            ->placeholder('-')
                            ->icon(Heroicon::FingerPrint),
                        Actions::make([
                            Action::make('reset_face')
                                ->color('danger')
                                ->label('Reset')
                                ->button()
                                ->icon(Heroicon::Trash)
                                ->requiresConfirmation()
                                ->modalHeading('KONFIRMASI')
                                ->modalWidth(Width::Small)
                                ->hidden(fn($record) => !$record?->user?->face_id)
                                ->modalDescription(fn($record) => 'konfirmasi untuk mereset Face ID')
                                ->action(function ($record) {
                                    $user = $record->user;
                                    if ($user) {
                                        try {
                                            $user->update([
                                                'face_id' => null,
                                            ]);

                                            Notification::make()
                                                ->title('Face ID berhasil direset')
                                                ->success()
                                                ->send();
                                        } catch (\Throwable $th) {
                                            Notification::make()
                                                ->title('Terjadi kesalahan saat mereset Face ID')
                                                ->danger()
                                                ->send();
                                        }
                                    }
                                })
                        ])->label(''),
                    ]),
            ]);
    }

    public static function posisiKaryawanSection(): Tabs
    {
        return Tabs::make('Posisi karyawan')
            ->tabs([
                Tab::make('Jabatan')
                    ->icon(Heroicon::Briefcase)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                        '2xl' => 4
                    ])
                    ->schema([
                        TextEntry::make('jabatan.nama_jabatan')
                            ->color('info')
                            ->badge()
                            ->label('Jabatan')
                            ->placeholder('-')
                            ->icon(Heroicon::Briefcase)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        TextEntry::make('jabatan.gajiAktif.gaji_bulanan')
                            ->color('info')
                            ->badge()
                            ->label('Gaji pokok')
                            ->placeholder('-')
                            ->prefix('Rp.')
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        TextEntry::make('jabatan.gajiAktif.gaji_lembur')
                            ->color('danger')
                            ->badge()
                            ->label('Gaji lembur')
                            ->placeholder('-')
                            ->prefix('Rp.')
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        Actions::make([
                            Action::make('detail_jabatan')
                                ->color('warning')
                                ->label('Detail')
                                ->badge()
                                ->url(function ($record) {
                                    return $record->whereHas('jabatan') ?  JabatanResource::getUrl('view', ['record' => $record?->jabatan?->id]) : null;
                                })
                                ->hidden(fn($record) => !$record?->jabatan()->exists())
                        ])->label('View'),
                    ]),

                Tab::make('Shift')
                    ->icon(Heroicon::Calendar)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 4,
                        '2xl' => 4
                    ])
                    ->schema([
                        TextEntry::make('shift.nama_shift')
                            ->color('info')
                            ->badge()
                            ->label('Shift')
                            ->placeholder('-')
                            ->icon(Heroicon::Calendar)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        TextEntry::make('shift.jam_masuk')
                            ->color('info')
                            ->badge()
                            ->label('Jam masuk')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        TextEntry::make('shift.jam_keluar')
                            ->color('danger')
                            ->badge()
                            ->label('Jam keluar')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        Actions::make([
                            Action::make('karyawan.detail_shift')
                                ->color('warning')
                                ->label('Detail')
                                ->badge()
                                ->url(function ($record) {
                                    return $record->whereHas('shift') ? ShiftResource::getUrl('view', ['record' => $record?->shift?->id]) : null;
                                })
                                ->hidden(fn($record) => !$record?->shift()->exists())
                        ])->label('View'),
                    ]),

                Tab::make('Kantor')
                    ->icon(Heroicon::BuildingOffice)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 3,
                        '2xl' => 3
                    ])
                    ->schema([
                        TextEntry::make('kantor.nama_kantor')
                            ->color('info')
                            ->badge()
                            ->label('Kantor')
                            ->placeholder('-')
                            ->icon(Heroicon::BuildingOffice2)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                        TextEntry::make('kantor.lokasi')
                            ->color('info')
                            ->badge()
                            ->label('Lokasi')
                            ->placeholder('-')
                            ->icon(Heroicon::MapPin)
                            ->url(fn($state) => $state != null ? 'https://www.google.com/maps/place/' . $state : null, true)
                            ->formatStateUsing(fn($state) => $state ?? '-'),
                    ]),
            ]);
    }

    public static function kehadiranSection(): Section
    {
        return Section::make('Performa kehadiran dalam 1 bulan')
            ->icon(Heroicon::CurrencyDollar)
            ->iconColor('info')
            ->description(function ($record) {
                $now = Carbon::now()->format('M-Y');
                return $now;
            })
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 2,
                'xl' => 4,
                '2xl' => 4
            ])
            ->schema([
                TextEntry::make('hadir')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        return $record->presensiBulanIni()->where('status_masuk', 'H')->count();
                    }),
                TextEntry::make('izin')
                    ->label('Izin')
                    ->badge()
                    ->color('info')
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        return $record->presensiBulanIni()->where('status_masuk', 'I')->count();
                    }),
                TextEntry::make('sakit')
                    ->label('Sakit')
                    ->badge()
                    ->color('warning')
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        return $record->presensiBulanIni()->where('status_masuk', 'S')->count();
                    }),

                TextEntry::make('tidak_absen_keluar')
                    ->label('Tidak Absen keluar')
                    ->badge()
                    ->color('danger')
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        return $record->presensiBulanIni()->where('status_keluar', null)->count();
                    }),

            ]);
    }
}
