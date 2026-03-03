<?php

namespace App\Filament\User\Resources\TidakHadirs;

use App\Filament\User\Resources\TidakHadirs\Pages\CreateTidakHadir;
use App\Filament\User\Resources\TidakHadirs\Pages\EditTidakHadir;
use App\Filament\User\Resources\TidakHadirs\Pages\ListTidakHadirs;
use App\Filament\User\Resources\TidakHadirs\Schemas\TidakHadirForm;
use App\Filament\User\Resources\TidakHadirs\Tables\TidakHadirsTable;
use App\Models\Karyawan;
use App\Models\TidakHadir;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TidakHadirResource extends Resource
{
    protected static ?string $model = Karyawan::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = "Today Alpa";
    protected static ?string $pluralModelLabel = "Today Alpa";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?int $navigationSort = 6;
    

    public static function table(Table $table): Table
    {
        return TidakHadirsTable::configure($table->modifyQueryUsing(function ($query) {
            return    $query->whereDoesntHave('presensiHariIni');
        }));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTidakHadirs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDoesntHave('presensiHariIni')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Jumlah karyawan yang tidak melakukan absen hari ini';
    }
}
