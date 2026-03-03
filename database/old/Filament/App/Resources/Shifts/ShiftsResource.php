<?php

namespace App\Filament\App\Resources\Shifts;

use App\Filament\App\Resources\Shifts\Pages\CreateShifts;
use App\Filament\App\Resources\Shifts\Pages\EditShifts;
use App\Filament\App\Resources\Shifts\Pages\ListShifts;
use App\Filament\App\Resources\Shifts\Pages\ViewShifts;
use App\Filament\App\Resources\Shifts\Schemas\ShiftsForm;
use App\Filament\App\Resources\Shifts\Schemas\ShiftsInfolist;
use App\Filament\App\Resources\Shifts\Tables\ShiftsTable;
use App\Models\Shifts;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ShiftsResource extends Resource
{
    protected static ?string $model = Shifts::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static ?string $navigationLabel = "Shift";
    protected static ?string $pluralModelLabel = "Shift";
    protected static string | UnitEnum | null $navigationGroup = 'Employee Management';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ShiftsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShiftsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftsTable::configure($table);
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
            'index' => ListShifts::route('/'),
            'create' => CreateShifts::route('/create'),
            'view' => ViewShifts::route('/{record}'),
            'edit' => EditShifts::route('/{record}/edit'),
        ];
    }
}
