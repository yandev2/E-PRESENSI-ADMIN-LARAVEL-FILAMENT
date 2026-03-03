<?php

namespace App\Filament\User\Resources\Jabatans\RelationManagers;

use App\Filament\User\Componen\Button\AddButtonComponen;

use App\Filament\User\Resources\Jabatans\Pages\EditJabatan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Support\RawJs;

class GajiRelationManager extends RelationManager
{
    protected static string $relationship = 'gaji';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::gajiForm()
            ]);
    }

    public static function gajiForm(): Grid
    {
        return Grid::make()
            ->columnSpanFull()
            ->schema([
                TextInput::make('gaji_bulanan')
                    ->required()
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $toInt = fn($value) => (int) str_replace([',', '.'], '', $value);

                        $hariKerja = (int) $get('jumlah_hari_kerja');

                        if ($state && $hariKerja > 0) {
                            $gajiBulanan = $toInt($state);
                            $gajiHarian  = round($gajiBulanan / $hariKerja);

                            $set('gaji_harian',  number_format(round($gajiHarian), 0, ',', '.'));
                        }
                    }),
                TextInput::make('gaji_harian')
                    ->required()
                    ->prefix('Rp.')
                    ->disabled()
                    ->dehydrated()
                    ->mask(RawJs::make('$money($input)')),
                TextInput::make('gaji_lembur')
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)')),
                TextInput::make('potongan_sakit')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_izin')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_alpha')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('potongan_tidak_absen_keluar')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                TextInput::make('jumlah_hari_kerja')
                    ->label('Jumlah hari kerja')
                    ->numeric()
                    ->reactive()
                    ->suffix('H')
                    ->default(26)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $toInt = fn($value) => (int) str_replace([',', '.'], '', $value);
                        $hariKerja = (int) $state;

                        if ($state && $hariKerja > 0) {
                            $gajiBulanan = $toInt($get('gaji_bulanan'));
                            $gajiHarian  = round($gajiBulanan / $hariKerja);

                            $set('gaji_harian',  number_format(round($gajiHarian), 0, ',', '.'));
                        }
                    }),
                DatePicker::make('berlaku_dari')
                    ->prefixIcon(Heroicon::Calendar)
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->default(now())
                    ->native(false),
                Hidden::make('status')
                    ->required()
                    ->default('aktif'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('gaji_bulanan')
            ->heading('')
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('nik')
            ->emptyStateHeading('TIDAK ADA GAJI')
            ->emptyStateDescription('belum ada gaji ditambahkan dalam jabatan ini')
            ->columns(static::gajiColumn())
            ->headerActions([
                AddButtonComponen::make()
                    ->label('Perbarui Gaji')
                    ->authorize(true)
                    ->modalIcon(Heroicon::Plus)
                    ->modalWidth(Width::FitContent)
                    ->modalAlignment(Alignment::Left)
                    ->modalHeading('PERBARUI GAJI')
                    ->modalDescription('Perbarui data gaji pada jabatan ini')
                    ->successNotification(null)
                    ->after(fn($record, $livewire) => static::handleAfterUpdate($record, $livewire)),
            ]);
    }

    public static function gajiColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('gaji_bulanan')
                ->badge()
                ->prefix('Rp.')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('gaji_lembur')
                ->badge()
                ->prefix('Rp.')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('potongan_sakit')
                ->label('Sakit')
                ->badge()
                ->suffix('%')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('potongan_izin')
                ->label('Izin')
                ->badge()
                ->suffix('%')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('potongan_alpha')
                ->label('Alpha')
                ->badge()
                ->suffix('%')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('potongan_tidak_absen_keluar')
                ->label('Tidak absen keluar')
                ->badge()
                ->suffix('%')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('jumlah_hari_kerja')
                ->label('Hari kerja')
                ->badge()
                ->suffix('%')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->searchable(),
            TextColumn::make('berlaku_sampai')
                ->label('Aktif sampai')
                ->date('d-M-Y')
                ->color(fn($record) => $record->status == 'aktif' ? 'info' : 'danger')
                ->icon(Heroicon::CalendarDateRange)
                ->default(Carbon::now()->format('Y-M-d'))
                ->searchable(),
        ];
    }

    public static function handleAfterUpdate($record, $livewire)
    {
        try {
            $gajiLama = $livewire->ownerRecord
                ->gaji()
                ->orderBy('created_at', 'desc')
                ->skip(1)
                ->first();

            Log::info($gajiLama);
            if ($gajiLama) {
                $gajiLama->update([
                    "status" => "non aktif",
                    'berlaku_sampai' =>  Carbon::parse($record->berlaku_dari)->subDay(),
                ]);
            }
            Notification::make()
                ->title('Okay')
                ->body('gaji telah berhasil diperbarui pada jabatan ini')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            if ($record?->exists) {
                $record->delete();
            }
            Notification::make()
                ->title('Ops')
                ->body($th->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return $pageClass != EditJabatan::class;
    }
}
