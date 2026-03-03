<?php

namespace App\Filament\User\Resources\LiveLocationkaryawans;

use App\Filament\User\Componen\Button\ViewButtonComponen;
use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Filament\User\Resources\LiveLocationkaryawans\Pages\ManageLiveLocationkaryawans;
use App\Models\Kantor;
use App\Models\LiveLocationkaryawan;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use UnitEnum;

class LiveLocationkaryawanResource extends Resource
{
    protected static ?string $model = LiveLocationkaryawan::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;
    protected static ?string $navigationLabel = "Tracking Location";
    protected static ?string $pluralModelLabel = "Tracking Location";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?string $recordTitleAttribute = 'address';

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()
            ->poll('10s')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA KARYAWAN')
            ->emptyStateDescription('belum ada karyawan online hari ini')
            ->columns(static::column())
            ->filters(static::filter())
            ->filtersFormWidth('md')
            ->filtersFormColumns(1)
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->recordActions([
                ViewButtonComponen::make()
                    ->url(fn($record) => $record->whereHas('karyawan') ? KaryawanResource::getUrl('view', ['record' => $record?->karyawan?->id]) : null),
            ])
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
            );
    }



    public static function column(): array
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
            TextColumn::make('karyawan.kantor.nama_kantor')
                ->label('Kantor')
                ->icon(Heroicon::BuildingOffice)
                ->searchable(),
            TextColumn::make('address')
                ->label('Alamat')
                ->icon(Heroicon::Map)
                ->searchable(),
            TextColumn::make('updated_at')
                ->label('update')
                ->badge()
                ->color(function ($state) {
                    $updatedAt = Carbon::parse($state);
                    $now = Carbon::now();
                    if ($updatedAt->diffInSeconds($now) <= 60) {
                        return 'success';
                    }
                    return 'danger';
                }),
        ];
    }

    public static function filter(): array
    {
        return [
            SelectFilter::make('kantor')
                ->label('Kantor')
                ->native(false)
                ->searchable()
                ->options(Kantor::pluck('nama_kantor', 'id'))
                ->query(function ($query, $data) {
                    $q = $data['value'];
                    if (blank($q)) return;

                    $query->whereHas('karyawan', function ($karyawan) use ($q) {
                        $karyawan->where('kantor_id', $q);
                    });
                }),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLiveLocationkaryawans::route('/'),
        ];
    }
}
