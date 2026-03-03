<?php

namespace App\Filament\App\Resources\Shifts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('jam_masuk')
                    ->time()
                    ->badge()
                    ->sortable()
                    ->color('success'),
                TextColumn::make('jam_keluar')
                    ->time()
                    ->badge()
                    ->sortable()
                    ->color('warning'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->button(),
                EditAction::make()
                    ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
