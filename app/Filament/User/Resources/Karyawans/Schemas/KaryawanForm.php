<?php

namespace App\Filament\User\Resources\Karyawans\Schemas;

use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\Kantor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\Rule;

class KaryawanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        static::userSection(),
                        static::kepegawaianSection()
                    ]),
                static::karyawanSection(),
                static::nokSection()
            ]);
    }

    public static function userSection(): Section
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->heading('Akun karyawan')
            ->icon(Heroicon::OutlinedUserCircle)
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 1,
                '2xl' => 2
            ])
            ->schema([
                FileUpload::make('user.avatar')
                    ->label('Foto')
                    ->columnSpan(1)
                    ->hiddenLabel()
                    ->disk('public')
                    ->maxSize(10240)
                    ->openable()
                    ->downloadable()
                    ->alignCenter()
                    ->panelLayout('integrated')
                    ->imageEditorMode(2)
                    ->loadingIndicatorPosition('center')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('center')
                    ->uploadProgressIndicatorPosition('center')
                    ->directory(fn() => auth()->user()->perusahaan_id . '/perusahaan/karyawan/profile')
                    ->image()
                    ->panelAspectRatio('1:1'),

                Grid::make(1)
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Username')
                            ->required(),
                        TextInput::make('user.email')
                            ->label('Email')
                            ->rules([
                                fn($record) => Rule::unique('users', 'email')
                                    ->ignore($record?->user?->id),
                            ])
                            ->required(),
                        TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($record) => !$record),
                        Hidden::make('user.is_owner')
                            ->default(false),
                    ]),
            ]);
    }

    public static function nokSection(): Section
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->heading('Keluarga karyawan')
            ->icon(Heroicon::OutlinedUserGroup)
            ->columnSpanFull()
            ->schema([
                Repeater::make('nok')
                    ->relationship('nok')
                    ->hiddenLabel()
                    ->addActionLabel('Tambah')
                    ->columnSpanFull()
                    ->compact()
                    ->addActionAlignment(Alignment::Right)
                    ->table([
                        TableColumn::make('nama')->markAsRequired(),
                        TableColumn::make('kontak')->markAsRequired(),
                        TableColumn::make('hubungan')->markAsRequired()->width('150px'),
                        TableColumn::make('alamat')->markAsRequired(),
                    ])
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('kontak')
                            ->label('Kontak')
                            ->required(),
                        Select::make('hubungan')
                            ->native(false)
                            ->placeholder('')
                            ->required()
                            ->options([
                                'ayah' => "Ayah",
                                'ibu' => "Ibu",
                                'saudara' => "Saudara",
                                "kerabat" => "Kerabat",
                            ]),
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->required(),
                    ])
            ]);
    }

    public static function kepegawaianSection(): Section
    {
        return Section::make()
            ->extraAttributes(['class' => 'form-section-custom'])
            ->columnSpanFull()
            ->icon(Heroicon::Briefcase)
            ->heading('Status Kepegawaian')
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 2
            ])
            ->schema([
                TextInput::make('nip')
                    ->label('NIP'),
                Select::make('tipe_karyawan')
                    ->label('Tipe karyawan')
                    ->placeholder('')
                    ->native(false)
                    ->required()
                    ->options([
                        'tetap' => 'tetap',
                        'magang' => 'magang',
                        'kontrak' => 'kontrak',
                        'paruh_waktu' => 'paruh waktu'
                    ]),
                Grid::make()
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 3,
                        '2xl' => 3
                    ])
                    ->schema([
                        Select::make('status_karyawan')
                            ->label('Status karyawan')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'aktif' => 'aktif',
                                'non_aktif' => 'non aktif',
                                'cuti' => 'cuti',
                                'resign' => 'resign'
                            ]),
                        Select::make('status_dinas')
                            ->label('Status dinas')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'dalam' => 'dalam',
                                'luar' => 'luar',
                            ]),
                        Select::make('kantor_id')
                            ->placeholder('')
                            ->prefixIcon(Heroicon::BuildingOffice)
                            ->native(false)
                            ->label('Kantor')
                            ->searchable()
                            ->options(Kantor::pluck('nama_kantor', 'id')),
                    ])
            ]);
    }

    public static function karyawanSection(): Grid
    {
        return Grid::make()
            ->schema([
                Section::make()
                    ->extraAttributes(['class' => 'form-section-custom'])
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->heading('Data karyawan')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 3
                    ])
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK')
                            ->required(),
                        TextInput::make('nomor_telp')
                            ->label('Kontak')
                            ->prefixIcon(Heroicon::Phone)
                            ->required(),
                        Select::make('agama')
                            ->label('Agama')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'islam' => 'Islam',
                                'kristen' => 'Kristen',
                                'katolik' => 'Katolik',
                                'hindu' => 'Hindu',
                                'buddha' => 'Buddha',
                                'konghucu' => 'Konghucu',
                            ]),
                        Select::make('status_pernikahan')
                            ->label('Status pernikahan')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'lajang' => 'Lajang',
                                'menikah' => 'Menikah',
                                'duda' => 'Duda',
                                'janda' => 'Janda',
                            ]),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'l' => 'LAKI-LAKI',
                                'p' => 'PEREMPUAN',
                            ]),
                        DatePicker::make('tanggal_lahir')
                            ->prefixIcon(Heroicon::Calendar)
                            ->displayFormat('d-M-Y')
                            ->format('d-M-Y')
                            ->native(false)
                            ->required(),
                        TextInput::make('tempat_lahir')
                            ->columnSpanFull()
                            ->required(),
                        Textarea::make('alamat_ktp')
                            ->columnSpanFull()
                            ->maxLength(255),
                        Textarea::make('alamat_domisili')
                            ->columnSpanFull()
                            ->rows(2)
                            ->maxLength(255),
                        Select::make('pendidikan_terakhir')
                            ->label('Pendidikan Terakhir')
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->options([
                                'tidak_sekolah' => 'Tidak / Belum Sekolah',
                                'sd'            => 'SD / MI',
                                'smp'           => 'SMP / MTs',
                                'sma'           => 'SMA / SMK / MA',
                                'd1'            => 'Diploma 1 (D1)',
                                'd2'            => 'Diploma 2 (D2)',
                                'd3'            => 'Diploma 3 (D3)',
                                'd4'            => 'Diploma 4 (D4) / Sarjana Terapan',
                                's1'            => 'Sarjana (S1)',
                                's2'            => 'Magister (S2)',
                                's3'            => 'Doktor (S3)',
                            ]),
                        Select::make('jabatan_id')
                            ->placeholder('')
                            ->native(false)
                            ->label('Jabatan')
                            ->searchable()
                            ->options(Jabatan::pluck('nama_jabatan', 'id')),
                        Select::make('shift_id')
                            ->placeholder('')
                            ->prefixIcon(Heroicon::OutlinedClock)
                            ->native(false)
                            ->label('Shift')
                            ->searchable()
                            ->options(Shift::pluck('nama_shift', 'id')),
                    ]),
            ]);
    }
}
