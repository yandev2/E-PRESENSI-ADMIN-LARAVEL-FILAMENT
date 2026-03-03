<?php

namespace App\Filament\Resources\Positions\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'salaries';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('gaji_bulanan')
                    ->required()
                    ->numeric(),
                TextInput::make('gaji_lembur')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('hari_kerja')
                    ->required()
                    ->numeric(),
                TextInput::make('potongan_alpha_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('potongan_izin_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('potongan_sakit_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('effective_from')
                    ->format('d M Y')
                    ->displayFormat('d M Y')
                    ->prefixIcon('heroicon-m-calendar')
                    ->native(false)
                    ->required(),
                DatePicker::make('effective_to')
                    ->format('d M Y')
                    ->displayFormat('d M Y')
                    ->prefixIcon('heroicon-m-calendar')
                    ->native(false)
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('gaji_bulanan')
                    ->numeric(),
                TextEntry::make('gaji_lembur')
                    ->numeric(),
                TextEntry::make('hari_kerja')
                    ->numeric(),
                TextEntry::make('potongan_alpha_persen')
                    ->numeric(),
                TextEntry::make('potongan_izin_persen')
                    ->numeric(),
                TextEntry::make('potongan_sakit_persen')
                    ->numeric(),
                TextEntry::make('effective_from')
                    ->date(),
                TextEntry::make('effective_to')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('belum ada data ditambahkan')
            ->defaultPaginationPageOption('5')
            ->heading('')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('gaji_bulanan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gaji_lembur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hari_kerja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('potongan_alpha_persen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('potongan_izin_persen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('potongan_sakit_persen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('effective_from')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('effective_to')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Salaries')
                    ->color('info')
                    ->modalHeading('Create Salaries')
                    ->modalIcon(Heroicon::CurrencyDollar)
                    ->icon(Heroicon::CurrencyDollar),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
