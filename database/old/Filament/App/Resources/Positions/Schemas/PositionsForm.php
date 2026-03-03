<?php

namespace App\Filament\App\Resources\Positions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;

class PositionsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('')
                    ->schema([
                        TextInput::make('kode')
                            ->required(),
                        TextInput::make('nama')
                            ->required(),
                        TextInput::make('golongan')
                            ->columnSpanFull(),
                        Textarea::make('deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1 
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 3
                    ]),


                Repeater::make('salaries')
                    ->hiddenLabel()
                    ->relationship('salaries')
                    ->schema([
                        TextInput::make('gaji_bulanan')
                            ->prefix('Rp.')
                            ->mask(RawJs::make('$money($input)'))
                            ->required(),
                        TextInput::make('gaji_lembur')
                            ->prefix('Rp.')
                            ->mask(RawJs::make('$money($input)'))
                            ->required(),
                        TextInput::make('hari_kerja')
                            ->suffix('H')
                            ->numeric(),
                        TextInput::make('potongan_alpha_persen')
                            ->label('Potongan alpha')
                            ->suffix('%')
                            ->numeric(),
                        TextInput::make('potongan_izin_persen')
                            ->label('Potongan izin')
                            ->suffix('%')
                            ->numeric(),
                        TextInput::make('potongan_sakit_persen')
                            ->label('Potongan sakit')
                            ->suffix('%')
                            ->numeric(),
                        DatePicker::make('effective_from')
                            ->label('Aktif sampai')
                            ->prefixIcon(Heroicon::Calendar)
                            ->displayFormat('d-M-Y')
                            ->format('d-M-Y')
                            ->default(now())
                            ->columnSpan(2)
                            ->native(false),

                    ])
                    ->grid(1)
                    ->addable(false)
                    ->deletable(false)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1 
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),


                Section::make('Active Salaries')
                    ->schema([
                        TextEntry::make('gaji_bulanan')
                            ->prefix('Rp.')
                            ->state(fn($record): string => $record->activeSalaries?->gaji_bulanan ?? '-'),
                        TextEntry::make('gaji_lembur')
                            ->prefix('Rp.')
                            ->state(fn($record): string => $record->activeSalaries?->gaji_lembur ?? '-'),
                        TextEntry::make('potongan_alpha_persen')
                            ->Label('Potongan alpha')
                            ->suffix('%')
                            ->state(fn($record): string => $record->activeSalaries?->potongan_alpha_persen ?? '-'),
                        TextEntry::make('potongan_izin_persen')
                            ->Label('Potongan izin')
                            ->suffix('%')
                            ->state(fn($record): string => $record->activeSalaries?->potongan_izin_persen ?? '-'),
                        TextEntry::make('potongan_sakit_persen')
                            ->Label('Potongan sakit')
                            ->suffix('%')
                            ->state(fn($record): string => $record->activeSalaries?->potongan_sakit_persen ?? '-'),
                        TextEntry::make('effective_from')
                            ->state(fn($record): string => $record->activeSalaries?->effective_from ?? '-'),
                        TextEntry::make('effective_from')
                            ->state(fn($record): string => $record->activeSalaries?->hari_kerja ?? '-'),
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 1 
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2
                    ])
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),


            ]);
    }
}
