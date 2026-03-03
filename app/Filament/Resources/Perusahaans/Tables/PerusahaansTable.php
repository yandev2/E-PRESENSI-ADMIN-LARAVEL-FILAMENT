<?php

namespace App\Filament\Resources\Perusahaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerusahaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Foto')
                    ->width(80)
                    ->disk('public')
                    ->circular(),
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('nama_perusahaan')
                    ->icon(Heroicon::BuildingOffice2)
                    ->badge()
                    ->color('success')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('kontak')
                    ->icon(Heroicon::Phone)
                    ->label('Kontak')
                    ->searchable(),
                TextColumn::make('email')
                    ->icon(Heroicon::Envelope)
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->icon(Heroicon::Calendar)
                    ->label('Begabung')
                    ->dateTime()
                    ->badge()
                    ->color('success')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->button(),
                EditAction::make()
                    ->button(),
                DeleteAction::make()
                    ->button(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
