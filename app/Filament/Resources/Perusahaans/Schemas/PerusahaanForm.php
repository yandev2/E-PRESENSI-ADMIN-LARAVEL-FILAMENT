<?php

namespace App\Filament\Resources\Perusahaans\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('accounts')
                    ->relationship(
                        name: 'user',
                        modifyQueryUsing: fn($query) => $query->where('is_owner', true)
                    )
                    ->maxItems(1)
                    ->hiddenLabel()
                    ->grid(1)
                    ->addable(false)
                    ->deletable(false)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
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
                            ->default(true)
                    ]),

                
                Section::make('Perusahan')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                        '2xl' => 2
                    ])
                    ->schema([
                        TextInput::make('nama_perusahaan')
                            ->prefixIcon(Heroicon::BuildingOffice2)
                            ->label('Nama perusahaan')
                            ->required(),
                        TextInput::make('email')
                            ->prefixIcon(Heroicon::Envelope)
                            ->label('Email')
                            ->required(),
                        TextInput::make('kontak')
                            ->prefixIcon(Heroicon::Phone)
                            ->label('Kontak')
                            ->required(),
                        Select::make('status')
                            ->native(false)
                            ->label('Status')
                            ->default('aktif')
                            ->options([
                                "aktif" => "Aktif",
                                "non aktif" => "Non Aktif"
                            ]),
                    ]),
            ]);
    }
}
