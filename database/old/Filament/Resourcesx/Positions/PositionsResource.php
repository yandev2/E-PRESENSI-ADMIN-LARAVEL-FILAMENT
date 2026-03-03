<?php

namespace App\Filament\Resources\Positions;

use App\Filament\Resources\Positions\Pages\CreatePositions;
use App\Filament\Resources\Positions\Pages\EditPositions;
use App\Filament\Resources\Positions\Pages\ListPositions;
use App\Filament\Resources\Positions\Pages\ViewPositions;
use App\Filament\Resources\Positions\RelationManagers\SalariesRelationManager;
use App\Filament\Resources\Positions\Schemas\PositionsForm;
use App\Filament\Resources\Positions\Schemas\PositionsInfolist;
use App\Filament\Resources\Positions\Tables\PositionsTable;
use App\Models\Positions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PositionsResource extends Resource
{
    protected static ?string $model = Positions::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
