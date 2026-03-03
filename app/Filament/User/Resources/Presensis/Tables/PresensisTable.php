<?php

namespace App\Filament\User\Resources\Presensis\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\MediaPriviewButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Models\Jabatan;
use App\Models\Shift;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('tanggal')
            ->groups([
                Group::make('tanggal')
                    ->label('Tanggal')
                    ->titlePrefixedWithLabel(false)
                    ->date()
                    ->collapsible(),
                Group::make('karyawan.user.name')
                    ->label('Karyawan')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->groupingDirectionSettingHidden()
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA PRESENSI')
            ->emptyStateDescription('tidak ada presensi yang ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::presensiColumn())
            ->filters(static::filter())
            ->filtersFormWidth('md')
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->badgeColor('danger')
                    ->color('primary')
                    ->label('Filter'),
            )
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->badge()
                    ->color('info')
                    ->label('Terapkan Filter')
            )
            ->recordActions([
                ViewButtonComponen::make(),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
              
            ]);
    }

    public static function filter(): array
    {
        return [
            SelectFilter::make('status_masuk')
                ->label('Status Masuk')
                ->native(false)
                ->options([
                    'H' => 'Hadir',
                    'I' => 'Izin',
                    'S' => 'Sakit',
                ]),
            SelectFilter::make('status_keluar')
                ->label('Status Keluar')
                ->native(false)
                ->options([
                    'H' => 'Hadir',
                    'I' => 'Izin',
                    'S' => 'Sakit',
                    'A' => 'Alpha'
                ]),
            SelectFilter::make('jabatan')
                ->native(false)
                ->options(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('jabatan', function ($karyawanQuery) use ($q) {
                        $karyawanQuery->where('nama_jabatan', $q);
                    });
                }),
            SelectFilter::make('shift')
                ->native(false)
                ->options(Shift::pluck('nama_shift', 'nama_shift'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('shift', function ($karyawanQuery) use ($q) {
                        $karyawanQuery->where('nama_shift', $q);
                    });
                }),
            Filter::make('created_at')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    DatePicker::make('Dari tanggal')->native(false),
                    DatePicker::make('Sampai tanggal')->native(false),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['Dari tanggal'],
                            fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date)
                                ->when(
                                    $data['Sampai tanggal'],
                                    fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date)
                                )
                        );
                }),
        ];
    }

    public static function presensiColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('karyawan.user.name')
                ->label('Nama')
                ->icon(Heroicon::UserCircle)
                ->searchable(),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color('info')
                ->separator()
                ->listWithLineBreaks()
                ->getStateUsing(function ($record) {
                    $masuk  = $record->status_masuk ?? '-';
                    $keluar = $record->status_keluar ?? 'A';
                    return "Masuk: {$masuk},Keluar: {$keluar}";
                }),
            TextColumn::make('jam')
                ->label('Jam')
                ->badge()
                ->color('success')
                ->separator()
                ->listWithLineBreaks()
                ->getStateUsing(function ($record) {
                    $masuk  = $record->jam_masuk ?? '-';
                    $keluar = $record->jam_keluar ?? '-';
                    return "Masuk: {$masuk},Keluar: {$keluar}";
                }),
            TextColumn::make('lokasi_masuk')
                ->label('Lokasi Masuk')
                ->badge()
                ->color('info')
                ->icon(Heroicon::MapPin)
                ->searchable()
                ->getStateUsing(fn($record) => $record->lokasi_masuk ?? '-'),
            TextColumn::make('lokasi_keluar')
                ->label('Lokasi Keluar')
                ->badge()
                ->color('danger')
                ->icon(Heroicon::MapPin)
                ->searchable()
                ->getStateUsing(fn($record) => $record->lokasi_keluar ?? '-'),
            TextColumn::make('shift_id')
                ->label('Shift')
                ->icon(Heroicon::Calendar)
                ->searchable()
                ->sortable()
                ->getStateUsing(fn($record) => $record->shift?->nama_shift ?? '-'),
            IconColumn::make('is_lembur')
                ->label('Lembur')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-minus-circle')
                ->trueColor('success')
                ->falseColor('gray')
                ->alignCenter()
        ];
    }

    public static function summaryKehadiran()
    {
        return Summarizer::make()
            ->label('Rekap Kehadiran')
            ->using(function ($query) {

                // ===== STATUS MASUK =====
                $masuk = $query
                    ->selectRaw('status_masuk, COUNT(*) as total')
                    ->groupBy('status_masuk')
                    ->pluck('total', 'status_masuk');

                // ===== STATUS KELUAR (NULL = A) =====
                $keluar = $query
                    ->selectRaw("
                    CASE
                        WHEN status_keluar IS NULL THEN 'A'
                        ELSE 'H'
                    END as status_keluar,
                    COUNT(*) as total
                ")
                    ->groupBy('status_keluar')
                    ->pluck('total', 'status_keluar');

                if ($masuk->isEmpty() && $keluar->isEmpty()) {
                    return '-';
                }

                $labels = [
                    'H' => 'Hadir',
                    'A' => 'Alpa',
                    'S' => 'Sakit',
                    'I' => 'Izin',
                ];

                $result = [];

                // Masuk
                if ($masuk->isNotEmpty()) {
                    $result[] = 'Masuk: ' . $masuk
                        ->map(fn($total, $key) => ($labels[$key] ?? $key) . ": {$total}")
                        ->join(' | ');
                }

                // Keluar
                if ($keluar->isNotEmpty()) {
                    $result[] = 'Keluar: ' . $keluar
                        ->map(fn($total, $key) => ($labels[$key] ?? $key) . ": {$total}")
                        ->join(' | ');
                }

                return implode(' — ', $result);
            });
    }
}
