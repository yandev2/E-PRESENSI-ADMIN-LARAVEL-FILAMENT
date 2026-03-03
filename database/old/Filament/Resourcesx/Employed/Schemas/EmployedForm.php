<?php

namespace App\Filament\Resources\Employed\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

class EmployedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Employed Data')
                        ->icon(Heroicon::User)
                        ->schema([
                            Section::make('Account')
                                ->columnSpanFull()
                                ->columns([
                                    'sm' => 1,
                                    'md' => 3,
                                    'lg' => 3,
                                ])
                                ->icon('heroicon-o-finger-print')
                                ->schema([
                                    TextInput::make('name')
                                        ->required(),
                                    TextInput::make('email')
                                        ->label('Email address')
                                        ->unique(ignoreRecord: true)
                                        ->email()
                                        ->required(),
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable(filament()->arePasswordsRevealable())
                                        ->required(fn(string $context): bool => $context === 'create')
                                        ->dehydrated(fn($state) => filled($state))
                                ]),

                            Grid::make()
                                ->columns([
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                    'xl' => 3,
                                ])
                                ->columnSpanFull()
                                ->schema([
                                    FileUpload::make('avatar')
                                        ->image()
                                        ->hiddenLabel()
                                        ->maxSize(10240)
                                        ->imageEditor()
                                        ->disk('public')
                                        ->panelLayout('integrated')
                                        ->removeUploadedFileButtonPosition('right')
                                        ->uploadButtonPosition('center')
                                        ->uploadProgressIndicatorPosition('center')
                                        ->directory('user/avatar')
                                        ->openable()
                                        ->panelAspectRatio('1:1')
                                        ->columnSpan(1),

                                    Section::make('Employed Details')
                                        ->icon('heroicon-o-finger-print')
                                        ->relationship('userDetail')
                                        ->columnSpan(2)
                                        ->columns([
                                            'sm' => 1,
                                            'md' => 2,
                                            'lg' => 2,
                                        ])
                                        ->schema([
                                            Hidden::make('status')->default('on'),
                                            DatePicker::make('tanggal_masuk')
                                                ->format('d M Y')
                                                ->displayFormat('d M Y')
                                                ->prefixIcon('heroicon-m-calendar')
                                                ->native(false)
                                                ->label('Tanggal Masuk')
                                                ->required(),
                                            DatePicker::make('tanggal_keluar')
                                                ->format('d M Y')
                                                ->displayFormat('d M Y')
                                                ->prefixIcon('heroicon-m-calendar')
                                                ->native(false)
                                                ->label('Tanggal Keluar')
                                                ->disabled(),
                                            TextInput::make('nik')
                                                ->label('NIK')
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(255)
                                                ->required(),
                                            Select::make('status_karyawan')
                                                ->label('Employment Status')
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->placeholder('')
                                                ->required()
                                                ->options([
                                                    'tetap' => '1. tetap',
                                                    'kontrak' => '2. kontrak',
                                                    'magang' => '3. magang',
                                                    'freelance' => '4. freelance',
                                                ]),
                                            Select::make('position_id')
                                                ->label('Position')
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->relationship('position', 'nama')
                                                ->placeholder('')
                                                ->nullable(),
                                            Select::make('shift_id')
                                                ->label('Shift')
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->relationship('shift', 'nama')
                                                ->placeholder('')
                                                ->nullable(),
                                            Textarea::make('alamat_ktp')
                                                ->required()
                                                ->label('Alamat KTP'),
                                            Textarea::make('alamat_domisili')
                                                ->required()
                                                ->label('Alamat Domisili'),
                                        ])
                                ]),

                        ]),
                    Step::make('Other Data')
                        ->icon(Heroicon::Identification)
                        ->schema([
                            Section::make('User Employed Details')
                                ->icon('heroicon-o-finger-print')
                                ->columns([
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 3,
                                ])
                                ->relationship('userDetail')
                                ->schema([
                                    DatePicker::make('tanggal_lahir')
                                        ->format('d M Y')
                                        ->displayFormat('d M Y')
                                        ->prefixIcon('heroicon-m-calendar')
                                        ->native(false)
                                        ->required()
                                        ->label('Tanggal Lahir'),
                                    Select::make('jenis_kelamin')
                                        ->label('Jenis Kelamin')
                                        ->searchable()
                                        ->preload()
                                        ->native(false)
                                        ->placeholder('')
                                        ->required()
                                        ->options([
                                            'l' => '1. Laki-Laki',
                                            'p' => '2. Perempuan',
                                        ]),
                                    Select::make('status_pernikahan')
                                        ->label('Status Pernikahan')
                                        ->searchable()
                                        ->preload()
                                        ->native(false)
                                        ->placeholder('')
                                        ->required()
                                        ->options([
                                            'nikah' => '1. nikah',
                                            'lajang' => '2. lajang',
                                            'duda' => '3. duda',
                                            'janda' => '4. janda',
                                        ]),
                                    Select::make('agama')
                                        ->label('Agama')
                                        ->searchable()
                                        ->preload()
                                        ->native(false)
                                        ->placeholder('')
                                        ->required()
                                        ->options([
                                            'islam' => '1. Islam',
                                            'kristen_protestan' => '2. Kristen Protestan',
                                            'katholik' => '3. Katholik',
                                            'hindu' => '4. Hindu',
                                            'buddha' => '5. Buddha',
                                            'konghucu' => '6. Konghucu',
                                        ]),
                                    TextInput::make('tempat_lahir')
                                        ->required()
                                        ->label('Tempat Lahir'),

                                    TextInput::make('nomor_ponsel')
                                        ->label('Nomor Ponsel')
                                        ->tel()
                                        ->unique(ignoreRecord: true)
                                        ->required()
                                        ->maxLength(14),






                                ])
                        ]),
                    Step::make('Next Of Kin')
                        ->icon(Heroicon::Users)
                        ->schema([
                            Repeater::make('userNok')
                                ->hiddenLabel()
                                ->relationship('userNok')
                                ->addActionLabel('Add Hubungan')
                                ->columnSpan(2)
                                ->addActionAlignment(Alignment::Start)
                                ->table([
                                    TableColumn::make('Nama')->markAsRequired(),
                                    TableColumn::make('Hubungan')->markAsRequired(),
                                    TableColumn::make('Kontak')->markAsRequired(),
                                ])
                                ->schema([
                                    TextInput::make('nama')->label('Nama'),
                                    Select::make('hubungan')->label('Hubungan')
                                        ->native(false)
                                        ->searchable()
                                        ->placeholder('')
                                        ->options([
                                            'Ayah' => 'Ayah',
                                            'Ibu' => 'Ibu',
                                            'Suami' => 'Suami',
                                            'Istri' => 'Istri',
                                            'Anak' => 'Anak',
                                            'Kakak' => 'Kakak',
                                            'Adik' => 'Adik',
                                            'Saudara' => 'Saudara',
                                            'Saudari' => 'Saudari',
                                            'Kakek' => 'Kakek',
                                            'Nenek' => 'Nenek',
                                            'Paman' => 'Paman',
                                            'Bibi' => 'Bibi',
                                            'Keponakan' => 'Keponakan',
                                            'Sepupu' => 'Sepupu',
                                            'Mertua' => 'Mertua',
                                            'Menantu' => 'Menantu',
                                            'Ipar' => 'Ipar',
                                        ]),
                                    TextInput::make('kontak')->label('Kontak'),
                                ])
                        ]),
                ])
                    ->columnSpanFull()
            ]);
    }
}
