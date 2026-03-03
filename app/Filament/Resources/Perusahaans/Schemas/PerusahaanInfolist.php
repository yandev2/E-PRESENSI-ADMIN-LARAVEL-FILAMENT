<?php

namespace App\Filament\Resources\Perusahaans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PerusahaanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Perusahan')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                        '2xl' => 2
                    ])
                    ->schema([
                        TextEntry::make('nama_perusahaan')
                            ->icon(Heroicon::BuildingOffice2)
                            ->label('Perusahan'),
                        TextEntry::make('kontak')
                            ->icon(Heroicon::Phone)
                            ->label('Kontak')
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->icon(Heroicon::Envelope)
                            ->label('Email perusahaan')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->icon(fn($state) => match ($state) {
                                'aktif'   => Heroicon::CheckCircle,
                                'non aktif' => Heroicon::XCircle,
                            })
                            ->color(fn($state) => match ($state) {
                                'aktif' => 'success',
                                'non aktif' => 'danger',
                            })
                            ->badge()
                            ->placeholder('-'),
                    ]),

                Section::make('Owner Account')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])
                    ->schema([
                        TextEntry::make('owner.name')
                            ->icon(Heroicon::User)
                            ->label('Username'),
                        TextEntry::make('owner.email')
                            ->icon(Heroicon::Envelope)
                            ->label('Email')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
