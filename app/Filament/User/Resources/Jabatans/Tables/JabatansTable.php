<?php

namespace App\Filament\User\Resources\Jabatans\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class JabatansTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA JABATAN')
            ->emptyStateDescription('tidak ada jabatan yang ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::jabatanColumn())
            ->recordActions([
                ViewButtonComponen::make(),
                EditButtonComponen::make(),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                ExportButtonComponen::make()
                    ->modalHeading('Export Data Jabatan')
                    ->modalDescription('Export data jabatan dalam format pdf atau exel')
                    ->action(fn(Collection $records, $data) =>
                    redirect()->route('export.jabatan', [
                        'id' => collect($records)->pluck('id')->toArray(),
                        'type' => $data['type'],
                    ]))
            ]);
    }

    public static function jabatanColumn(): array
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('kode_jabatan')
                ->label('Kode')
                ->badge()
                ->color('info')
                ->searchable(),
            TextColumn::make('nama_jabatan')
                ->label('Jabatan')
                ->icon(Heroicon::Briefcase)
                ->searchable(),
            TextColumn::make('deskripsi')
                ->badge()
                ->color('warning')
                ->searchable(),
            TextColumn::make('gajiAktif')
                ->badge()
                ->color('info')
                ->sortable(
                    query: function ($query, $direction) {
                        $query
                            ->leftJoin('gajis as g', function ($join) {
                                $join->on('g.jabatan_id', '=', 'jabatans.id')
                                    ->where('g.status', 'aktif');
                            })
                            ->orderBy('g.gaji_bulanan', $direction)
                            ->select('jabatans.*');
                    }
                )
                ->searchable(query: function ($query, $search) {
                    $query->whereHas('gaji', function ($gajiQuery) use ($search) {
                        $gajiQuery->where('status', 'aktif')->where('gaji_bulanan', 'like', "%{$search}%");
                    });
                })
                ->formatStateUsing(function ($record) {
                    $gaji = $record->gaji?->firstWhere('status', 'aktif')?->gaji_bulanan;
                    return 'Rp.' . $gaji;
                }),
        ];
    }
}
