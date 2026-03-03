<?php

namespace App\Filament\App\Resources\Positions;

use App\Filament\App\Resources\Positions\Pages\CreatePositions;
use App\Filament\App\Resources\Positions\Pages\EditPositions;
use App\Filament\App\Resources\Positions\Pages\ListPositions;
use App\Filament\App\Resources\Positions\Pages\ViewPositions;
use App\Filament\App\Resources\Positions\Schemas\PositionsForm;
use App\Filament\App\Resources\Positions\Schemas\PositionsInfolist;
use App\Filament\App\Resources\Positions\Tables\PositionsTable;
use App\Filament\App\Resources\Positions\RelationManagers\SalariesRelationManager;
use App\Models\Positions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PositionsResource extends Resource
{
    protected static ?string $model = Positions::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;
    protected static ?string $navigationLabel = "Position";
    protected static ?string $pluralModelLabel = "Position";
    protected static string | UnitEnum | null $navigationGroup = 'Employee Management';   
     protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return PositionsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PositionsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SalariesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPositions::route('/'),
            'create' => CreatePositions::route('/create'),
            'view' => ViewPositions::route('/{record}'),
            'edit' => EditPositions::route('/{record}/edit'),
        ];
    }
}
