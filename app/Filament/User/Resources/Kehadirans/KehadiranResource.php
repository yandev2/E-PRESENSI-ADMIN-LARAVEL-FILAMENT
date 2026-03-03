<?php

namespace App\Filament\User\Resources\Kehadirans;

use App\Filament\User\Resources\Kehadirans\Pages\CreateKehadiran;
use App\Filament\User\Resources\Kehadirans\Pages\EditKehadiran;
use App\Filament\User\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\User\Resources\Kehadirans\Pages\ViewKehadiran;
use App\Filament\User\Resources\Kehadirans\Schemas\KehadiranForm;
use App\Filament\User\Resources\Kehadirans\Schemas\KehadiranInfolist;
use App\Filament\User\Resources\Kehadirans\Tables\KehadiransTable;
use App\Filament\User\Widgets\PresensiTodayWidget;
use App\Models\Kehadiran;
use App\Models\Presensi;
use BackedEnum;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class KehadiranResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartPie;
    protected static ?string $navigationLabel = "Today";
    protected static ?string $pluralModelLabel = "Today";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?int $navigationSort = 5;
  

    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table->modifyQueryUsing(function ($query) {
            return $query->whereDate('tanggal', Carbon::now())->orderByDesc('tanggal');
        }));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKehadirans::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('tanggal', Carbon::now())->orderByDesc('tanggal')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
