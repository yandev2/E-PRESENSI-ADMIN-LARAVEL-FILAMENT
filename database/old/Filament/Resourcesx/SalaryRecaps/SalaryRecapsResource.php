<?php

namespace App\Filament\Resources\SalaryRecaps;

use App\Filament\Resources\SalaryRecaps\Pages\CreateSalaryRecaps;
use App\Filament\Resources\SalaryRecaps\Pages\EditSalaryRecaps;
use App\Filament\Resources\SalaryRecaps\Pages\ListSalaryRecaps;
use App\Filament\Resources\SalaryRecaps\Pages\ViewSalaryRecaps;
use App\Filament\Resources\SalaryRecaps\Schemas\SalaryRecapsForm;
use App\Filament\Resources\SalaryRecaps\Schemas\SalaryRecapsInfolist;
use App\Filament\Resources\SalaryRecaps\Tables\SalaryRecapsTable;
use App\Models\SalaryRecaps;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryRecapsResource extends Resource
{
    protected static ?string $model = SalaryRecaps::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'status';

    public static function form(Schema $schema): Schema
    {
        return SalaryRecapsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalaryRecapsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryRecapsTable::configure($table);
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
            'index' => ListSalaryRecaps::route('/'),
            'create' => CreateSalaryRecaps::route('/create'),
            'view' => ViewSalaryRecaps::route('/{record}'),
            'edit' => EditSalaryRecaps::route('/{record}/edit'),
        ];
    }
}
