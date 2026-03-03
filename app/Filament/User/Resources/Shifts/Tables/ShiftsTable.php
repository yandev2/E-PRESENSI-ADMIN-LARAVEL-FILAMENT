<?php

namespace App\Filament\User\Resources\Shifts\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA SHIFT')
            ->emptyStateDescription('tidak ada shift yang ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::shiftColumn())
            ->recordActions([
                ViewButtonComponen::make(),
                EditButtonComponen::make(),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                ExportButtonComponen::make()
                    ->modalHeading('Export Data Shift')
                    ->modalDescription('Export data shift dalam format pdf atau exel')
                    ->action(fn(Collection $records, $data) =>
                    redirect()->route('export.shift', [
                        'id' => collect($records)->pluck('id')->toArray(),
                        'type' => $data['type'],
                    ]))
            ]);
    }

    public static function shiftColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('nama_shift')
                ->icon(Heroicon::Calendar)
                ->searchable()
                ->sortable(),
            TextColumn::make('jam_masuk')
                ->icon(Heroicon::Clock)
                ->badge()
                ->color('success')
                ->time()
                ->sortable(),
            TextColumn::make('jam_keluar')
                ->icon(Heroicon::Clock)
                ->badge()
                ->color('warning')
                ->time()
                ->sortable(),
        ];
    }
}
