<?php

namespace App\Filament\User\Resources\Karyawans\Schemas;

use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class KaryawanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('Akun karyawan')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 3
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Username')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($record) => !$record),
                        Hidden::make('is_owner')
                            ->default(false),
                        FileUpload::make('avatar')
                            ->label('Foto')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory(fn() => auth()->user()->perusahaan->nama_perusahaan. '/karyawan/profile')
                            ->image()
                            ->panelAspectRatio(2 / 3),
                    ]),

              

            ]);
    }
}
