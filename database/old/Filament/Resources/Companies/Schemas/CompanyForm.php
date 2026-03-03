<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
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
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required(fn($record) => !$record),
                    ])->columnSpan(2)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ]),

                Section::make('Info')
                    ->schema([
                        Textarea::make('about')
                            ->columnSpanFull(),
                        Textarea::make('address')
                            ->columnSpanFull(),
                        TextInput::make('location'),
                        Select::make('status')
                            ->options([
                                'active' => 'active',
                                'inactive' => 'inactive'
                            ])
                            ->placeholder('')
                            ->native(false)
                            ->required()
                            ->default('active'),
                    ])
                    ->columnSpan(1)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ]),

                Section::make('Company site')
                    ->schema([
                        FileUpload::make('logo')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('company')
                            ->image()
                            ->panelAspectRatio(2 / 3),
                        TextInput::make('site'),
                    ])
                    ->columnSpan(1)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ]),
            ]);
    }
}
