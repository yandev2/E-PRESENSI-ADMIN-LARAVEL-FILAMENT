<?php

namespace App\Filament\User\Resources\Izins;

use App\Filament\User\Resources\Izins\Pages\CreateIzin;
use App\Filament\User\Resources\Izins\Pages\EditIzin;
use App\Filament\User\Resources\Izins\Pages\ListIzins;
use App\Filament\User\Resources\Izins\Pages\ViewIzin;
use App\Filament\User\Resources\Izins\Schemas\IzinForm;
use App\Filament\User\Resources\Izins\Schemas\IzinInfolist;
use App\Filament\User\Resources\Izins\Tables\IzinsTable;
use App\Models\Izin;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IzinResource extends Resource
{
    protected static ?string $model = Izin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Envelope;
    protected static ?string $navigationLabel = "Draft Izin";
    protected static ?string $pluralModelLabel = "Draft Izin";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';

    protected static ?string $recordTitleAttribute = 'izin';


    public static function table(Table $table): Table
    {
        return IzinsTable::configure($table);
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
            'index' => ListIzins::route('/'),
        ];
    }
}
