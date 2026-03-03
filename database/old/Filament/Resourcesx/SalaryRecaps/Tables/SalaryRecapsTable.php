<?php

namespace App\Filament\Resources\SalaryRecaps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalaryRecapsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('position_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tahun')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_hadir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_alpha')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_izin')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gaji_bulanan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_potongan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gaji_diterima')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('file')
                    ->searchable(),
                TextColumn::make('generate_at')
                    ->date()
                    ->sortable(),
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
