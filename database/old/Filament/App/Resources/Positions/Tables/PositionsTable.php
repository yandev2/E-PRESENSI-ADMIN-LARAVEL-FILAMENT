<?php

namespace App\Filament\App\Resources\Positions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('golongan')
                    ->label('Golongan')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),

            ])
            ->groupingDirectionSettingHidden()
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('kode')
                    ->badge()
                    ->color('danger')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('golongan')
                    ->searchable(),
                TextColumn::make('activeSalaries.gaji_bulanan')
                    ->badge()
                    ->color('success')
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
