<?php

namespace App\Filament\Resources\EmployeeLiveLocations;

use App\Filament\Resources\EmployeeLiveLocations\Pages\CreateEmployeeLiveLocations;
use App\Filament\Resources\EmployeeLiveLocations\Pages\EditEmployeeLiveLocations;
use App\Filament\Resources\EmployeeLiveLocations\Pages\ListEmployeeLiveLocations;
use App\Filament\Resources\EmployeeLiveLocations\Pages\ViewEmployeeLiveLocations;
use App\Filament\Resources\EmployeeLiveLocations\Schemas\EmployeeLiveLocationsForm;
use App\Filament\Resources\EmployeeLiveLocations\Schemas\EmployeeLiveLocationsInfolist;
use App\Filament\Resources\EmployeeLiveLocations\Tables\EmployeeLiveLocationsTable;
use App\Models\EmployeeLiveLocations;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeLiveLocationsResource extends Resource
{
    protected static ?string $model = EmployeeLiveLocations::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'is_active';

    public static function form(Schema $schema): Schema
    {
        return EmployeeLiveLocationsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeLiveLocationsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeLiveLocationsTable::configure($table);
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
            'index' => ListEmployeeLiveLocations::route('/'),
            'create' => CreateEmployeeLiveLocations::route('/create'),
            'view' => ViewEmployeeLiveLocations::route('/{record}'),
            'edit' => EditEmployeeLiveLocations::route('/{record}/edit'),
        ];
    }
}
