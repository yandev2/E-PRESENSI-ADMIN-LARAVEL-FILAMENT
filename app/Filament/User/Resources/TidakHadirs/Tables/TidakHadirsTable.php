<?php

namespace App\Filament\User\Resources\TidakHadirs\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Models\Jabatan;
use App\Models\Shift;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class TidakHadirsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA KARYAWAN ALPA')
            ->emptyStateDescription('semua karyawan hadir pada hari ini')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::column())
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
                ViewButtonComponen::make()
                    ->url(function ($record) {
                        return   KaryawanResource::getUrl('view', ['record' => $record?->id]);
                    }),
            ]);
    }

    public static function column(): array
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            ImageColumn::make('user.avatar')
                ->label('Foto')
                ->width(80)
                ->circular()

                ->disk('public'),
            TextColumn::make('nip')
                ->icon(Heroicon::FingerPrint)
                ->searchable()
                ->badge()
                ->color('info'),
            TextColumn::make('user.name')
                ->label('Nama')
                ->icon(Heroicon::UserCircle)
                ->searchable(),
            TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->searchable()
                ->placeholder('-')
                ->formatStateUsing(fn($state) => match ($state) {
                    'l' => 'Laki Laki',
                    'p' => 'Perempuan'
                }),
            TextColumn::make('nomor_telp')
                ->label('Kontak')
                ->icon(Heroicon::Phone)
                ->searchable()
                ->placeholder('-'),
            TextColumn::make('jabatan.nama_jabatan')
                ->label('Jabatan')
                ->icon(Heroicon::Briefcase)
                ->placeholder('-')
                ->searchable(),
            TextColumn::make('shift.nama_shift')
                ->label('Shift')
                ->icon(Heroicon::Calendar)
                ->searchable()
                ->placeholder('-'),
        ];
    }

    public static function filter(): array
    {
        return [
            SelectFilter::make('jabatan')
                ->label('Jabatan')
                ->native(false)
                ->searchable()
                ->options(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('jabatan', function ($karyawan) use ($q) {
                        $karyawan->where('nama_jabatan', $q);
                    });
                }),

            SelectFilter::make('shift')
                ->label('Shift')
                ->searchable()
                ->native(false)
                ->options(Shift::pluck('nama_shift', 'nama_shift'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('shift', function ($karyawan) use ($q) {
                        $karyawan->where('nama_shift', $q);
                    });
                }),

            SelectFilter::make('status')
                ->label('Status karyawan')
                ->native(false)
                ->options([
                    'aktif' => 'aktif',
                    'non aktif' => 'non aktif',
                    'cuti' => 'cuti',
                    'resign' => 'resign'
                ])
                ->query(function ($query, $data) {
                    $value = $data['value'];
                    if (blank($value)) return;
                    $query->where('status_karyawan', $value);
                }),

            SelectFilter::make('tipe')
                ->label('Tipe karyawan')
                ->native(false)
                ->options([
                    'tetap' => 'tetap',
                    'magang' => 'magang',
                    'kontrak' => 'kontrak',
                    'paruh_waktu' => 'paruh_waktu'
                ])
                ->query(function ($query, $data) {
                    $value = $data['value'];
                    if (blank($value)) return;
                    $query->where('tipe_karyawan', $value);
                }),

            SelectFilter::make('jenis_kelamin')
                ->label('Jenis kelamin')
                ->native(false)
                ->searchable()
                ->columnSpanFull()
                ->options(['l' => 'Laki Laki', 'p' => 'Perempuan']),
        ];
    }
}
