<?php

namespace App\Filament\User\Resources\Karyawans\Tables;

use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\ExportButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\Kantor;
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

class KaryawansTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA KARYAWAN')
            ->emptyStateDescription('belum ada karyawan ditambahkan dalam instansi ini')
            ->columns(static::column())
            ->filters(static::filter())
            ->filtersFormWidth('md')
            ->filtersFormColumns(2)
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
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
                EditButtonComponen::make(),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                ExportButtonComponen::make()
                    ->modalHeading('Export Data Karyawan')
                    ->modalDescription('Export data karyawan dalam format pdf atau exel')
                    ->action(fn(Collection $records, $data) =>
                    redirect()->route('export.karyawan', [
                        'id' => collect($records)->pluck('user.id')->toArray(),
                        'type' => $data['type'],
                    ]))

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
            TextColumn::make('usia')
                ->label('Usia')
                ->sortable()
                ->suffix(' Tahun')
                ->badge()
                ->color(fn($state) => match (true) {
                    $state < 20 => 'success',
                    $state < 30 => 'info',
                    $state < 40 => 'warning',
                    $state > 50 => 'danger',
                    default => 'secondary', // usia 40-50
                })
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
                ->options(['l' => 'Laki Laki', 'p' => 'Perempuan']),

            SelectFilter::make('kantor')
                ->label('Kantor')
                ->native(false)
                ->searchable()
                ->options(Kantor::pluck('nama_kantor', 'nama_kantor'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('kantor', function ($karyawan) use ($q) {
                        $karyawan->where('nama_kantor', $q);
                    });
                }),

            Filter::make('usia')
                ->label('Usia')
                ->columnSpanFull()
                ->schema([
                    Section::make()
                        ->heading('Usia')
                        ->description('pilih rentang usia')
                        ->columnSpanFull()
                        ->columns([
                            'sm' => 1,
                            'md' => 1,
                            'lg' => 1,
                            'xl' => 2,
                            '2xl' => 2
                        ])
                        ->schema([
                            TextInput::make('min')
                                ->numeric()
                                ->suffix('Tahun')
                                ->required(),
                            TextInput::make('max')
                                ->numeric()
                                ->suffix('Tahun')
                                ->required(),
                        ])
                ])
                ->indicateUsing(function (array $data): ?string {
                    $min = $data['min'] != null ? 'Dari ' . $data['min']  . ' Tahun ' : '';
                    $max = $data['max'] != null ? 'Sampai ' .  $data['max'] . ' Tahun' : '';
                    return $min . $max;
                })
                ->query(function (Builder $query, array $data) {
                    if (empty($data['min']) && empty($data['max'])) {
                        return;
                    }
                    $query
                        ->when(
                            $data['min'],
                            fn($v) => $v->whereRaw(
                                'EXTRACT(YEAR FROM AGE(current_date, tanggal_lahir)) >= ?',
                                [$data['min']]
                            )
                        )
                        ->when(
                            $data['max'],
                            fn($v) => $v->whereRaw(
                                'EXTRACT(YEAR FROM AGE(current_date, tanggal_lahir))  <= ?',
                                [$data['max']]
                            )
                        );
                })
        ];
    }
}
