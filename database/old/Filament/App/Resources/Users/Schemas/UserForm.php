<?php

namespace App\Filament\App\Resources\Users\Schemas;

use App\Models\Positions;
use App\Models\Shifts;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Account')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($record) => !$record),
                    ])
                    ->columnSpan(2)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3
                    ]),


                Section::make('Detail')
                    ->relationship('userDetail')
                    ->schema([
                        TextInput::make('nik')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('tempat_lahir')
                            ->required(),
                        DatePicker::make('tanggal_lahir')
                            ->prefixIcon(Heroicon::Calendar)
                            ->displayFormat('d-M-Y')
                            ->format('d-M-Y')
                            ->native(false)
                            ->required(),
                        Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu',
                            ])
                            ->placeholder('')
                            ->native(false)
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->options([
                                'L' => 'LAKI-LAKI',
                                'P' => 'PEREMPUAN',
                            ])
                            ->placeholder('')
                            ->native(false)
                            ->required(),
                        Select::make('status_pernikahan')
                            ->options([
                                'lajang' => 'Lajang',
                                'menikah' => 'Menikah',
                                'duda' => 'Duda',
                                'janda' => 'Janda',

                            ])
                            ->placeholder('')
                            ->native(false)
                            ->required(),
                        Textarea::make('alamat_ktp')
                            ->columnSpanFull()
                            ->maxLength(255),
                        Textarea::make('alamat_domisili')
                            ->columnSpanFull()
                            ->maxLength(255),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 1
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2
                    ]),


                Grid::make()
                    ->schema([
                        Section::make('More')
                            ->relationship('userDetail')
                            ->schema([
                                Select::make('position_id')
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Jabatan')
                                    ->searchable()
                                    ->options(Positions::pluck('nama', 'id')),
                                Select::make('shift_id')
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Shift')
                                    ->searchable()
                                    ->options(Shifts::pluck('nama', 'id')),
                                TextInput::make('nomor_ponsel')
                                    ->tel()
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'on' => 'On',
                                        'off' => 'Off',
                                        'cuti' => 'Cuti',
                                        'resign' => 'Resign',
                                    ])
                                    ->placeholder('')
                                    ->native(false)
                                    ->default('on')
                                    ->required(),
                            ])
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2,
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2
                            ]),

                        FileUpload::make('avatar')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('user')
                            ->image()
                            ->panelAspectRatio(2 / 4)
                            ->getUploadedFileNameForStorageUsing(function ($file, callable $get, $livewire) {
                                $rootState = $livewire->data ?? [];
                                $name   = $rootState['name'];
                                $company_name = auth()->user()?->company->nama;
                                return "{$company_name}-{$name}." . $file->getClientOriginalExtension();
                            }),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 1
                    ]),


                Repeater::make('userDokumen')
                    ->relationship('userDokumen')
                    ->addActionLabel('Tambah Dokumen')
                    ->columnSpan(2)
                    ->addActionAlignment(Alignment::Start)
                    ->table([
                        TableColumn::make('jenis_dokumen')->markAsRequired(),
                        TableColumn::make('judul')->markAsRequired(),
                        TableColumn::make('deskripsi')->markAsRequired(),
                        TableColumn::make('tanggal_terbit')->markAsRequired(),
                        TableColumn::make('tanggal_expired')->markAsRequired(),
                    ])
                    ->schema([
                        TextInput::make('jenis_dokumen')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('judul')
                            ->label('Send To')
                            ->required(),
                        TextInput::make('deskripsi')
                            ->label('Deskripsi')
                            ->default('-'),
                        TextInput::make('tanggal_terbit')
                            ->label('Terbit')
                            ->required(),
                        TextInput::make('tanggal_expired')
                            ->label('Expired')
                    ])

            ]);
    }
}
