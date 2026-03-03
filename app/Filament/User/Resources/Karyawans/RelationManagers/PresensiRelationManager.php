<?php

namespace App\Filament\User\Resources\Karyawans\RelationManagers;

use App\Filament\App\Resources\Karyawans\Pages\EditKaryawan;
use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Presensis\PresensiResource;
use App\Models\Jabatan;
use App\Models\Shift;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PresensiRelationManager extends RelationManager
{
    protected static string $relationship = 'presensi';

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Presensi')
            ->badge($ownerRecord->presensi()->count())
            ->badgeColor('info')
            ->badgeTooltip('Presensi dari karyawan')
            ->icon(Heroicon::FingerPrint);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('perusahaan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('shift_id')
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                Textarea::make('status_masuk')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('status_keluar')
                    ->columnSpanFull(),
                TimePicker::make('jam_masuk'),
                TimePicker::make('jam_keluar'),
                TextInput::make('lokasi_masuk'),
                TextInput::make('lokasi_keluar'),
                TextInput::make('wajah_masuk'),
                TextInput::make('wajah_keluar'),
                Toggle::make('is_lembur')
                    ->required(),
                TextInput::make('file'),
                TextInput::make('jabatan_id')
                    ->numeric(),
                TextInput::make('gaji_id')
                    ->numeric(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema

            ->components([
                TextEntry::make('perusahaan_id')
                    ->numeric(),
                TextEntry::make('shift_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('status_masuk')
                    ->columnSpanFull(),
                TextEntry::make('status_keluar')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('jam_masuk')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('jam_keluar')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('lokasi_masuk')
                    ->placeholder('-'),
                TextEntry::make('lokasi_keluar')
                    ->placeholder('-'),
                TextEntry::make('wajah_masuk')
                    ->placeholder('-'),
                TextEntry::make('wajah_keluar')
                    ->placeholder('-'),
                IconEntry::make('is_lembur')
                    ->boolean(),
                TextEntry::make('file')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('jabatan_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('gaji_id')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->defaultGroup('tanggal')
            ->groups([
                Group::make('tanggal')
                    ->label('Bulan')
                    ->getTitleFromRecordUsing(
                        fn($record) =>
                        Carbon::parse($record->tanggal)
                            ->translatedFormat('F Y')
                    )
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
            ->recordTitleAttribute('tanggal')
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
                ViewButtonComponen::make()
                    ->url(function ($record) {
                        return $record->whereHas('karyawan') ?  PresensiResource::getUrl('view', ['record' => $record?->id]) : null;
                    }),
                DeleteButtonComponen::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function presensiColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->icon(Heroicon::Calendar)
                ->date('d')
                ->badge()
                ->color('info')
                ->searchable(),
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

                    $query->whereHas('karyawan.jabatan', function ($karyawanQuery) use ($q) {
                        $karyawanQuery->where('nama_jabatan', $q);
                    });
                }),
            SelectFilter::make('shift')
                ->native(false)
                ->options(Shift::pluck('nama_shift', 'nama_shift'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('karyawan.shift', function ($karyawanQuery) use ($q) {
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

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return $pageClass != EditKaryawan::class;
    }
}
