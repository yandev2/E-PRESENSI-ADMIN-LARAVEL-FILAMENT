<?php

namespace App\Filament\Resources\Izins;

use App\Filament\Resources\Izins\Pages\CreateIzins;
use App\Filament\Resources\Izins\Pages\EditIzins;
use App\Filament\Resources\Izins\Pages\ListIzins;
use App\Filament\Resources\Izins\Pages\ViewIzins;
use App\Filament\Resources\Izins\Schemas\IzinsForm;
use App\Filament\Resources\Izins\Schemas\IzinsInfolist;
use App\Filament\Resources\Izins\Tables\IzinsTable;
use App\Models\Izins;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IzinsResource extends Resource
{
    protected static ?string $model = Izins::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return IzinsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IzinsInfolist::configure($schema);
    }

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
            'create' => CreateIzins::route('/create'),
            'view' => ViewIzins::route('/{record}'),
            'edit' => EditIzins::route('/{record}/edit'),
        ];
    }
}
