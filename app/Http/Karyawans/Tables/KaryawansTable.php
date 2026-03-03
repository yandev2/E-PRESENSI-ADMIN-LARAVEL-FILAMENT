<?php

namespace App\Filament\User\Resources\Karyawans\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class KaryawansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(KaryawanColumn::configure())
            ->filters([
              8
            ])
            ->recordActions(
                [
                    EditAction::make()->color('info')->button(),
                    ViewAction::make()->color('success')->button()
                ]
            )
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
