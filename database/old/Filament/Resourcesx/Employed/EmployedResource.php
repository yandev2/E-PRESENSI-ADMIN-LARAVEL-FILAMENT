<?php

namespace App\Filament\Resources\Employed;

use App\Filament\Resources\Employed\Pages\CreateEmployed;
use App\Filament\Resources\Employed\Pages\EditEmployed;
use App\Filament\Resources\Employed\Pages\ListEmployed;
use App\Filament\Resources\Employed\Pages\ViewEmployed;
use App\Filament\Resources\Employed\RelationManagers\EmployedDokumenRelationManager;
use App\Filament\Resources\Employed\Schemas\EmployedForm;
use App\Filament\Resources\Employed\Schemas\EmployedInfolist;
use App\Filament\Resources\Employed\Tables\EmployedTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmployedResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = "Employees";
    protected static ?string $pluralModelLabel = "Employees";
    protected static string | UnitEnum | null $navigationGroup = 'Employed Management';

    public static function form(Schema $schema): Schema
    {
        return EmployedForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployedInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployedTable::configure($table->modifyQueryUsing(
            fn($query) =>
            $query->where('email', '!=', 'admin@gmail.com')
        ));
    }

    public static function getRelations(): array
    {
        return [
            EmployedDokumenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployed::route('/'),
            'create' => CreateEmployed::route('/create'),
            'view' => ViewEmployed::route('/{record}'),
            'edit' => EditEmployed::route('/{record}/edit'),
        ];
    }
}
