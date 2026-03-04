<?php

namespace App\Filament\User\Resources\Izins\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Presensis\PresensiResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class IzinsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA IZIN')
            ->emptyStateDescription('tidak ada izin yang ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('presensi.karyawan.user.name')
                    ->icon(Heroicon::UserCircle)
                    ->searchable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('presensi.tanggal')
                    ->label('Tanggal')
                    ->icon(Heroicon::Calendar)
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('deskripsi')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                ImageColumn::make('file')
                    ->label('File')
                    ->width(80)
                    ->openUrlInNewTab()
                    ->url(fn($state) => $state ? Storage::url($state) : null)
                    ->disk('public'),
            ])
            ->recordActions([
                ViewButtonComponen::make()
                    ->url(function ($record) {
                        return  PresensiResource::getUrl('view', ['record' => $record?->presensi->id]);
                    }),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                ]),
            ]);
    }
}
