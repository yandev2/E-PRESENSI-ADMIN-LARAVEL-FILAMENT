<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\CreateAttendances;
use App\Filament\Resources\Attendances\Pages\EditAttendances;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Pages\ViewAttendances;
use App\Filament\Resources\Attendances\Schemas\AttendancesForm;
use App\Filament\Resources\Attendances\Schemas\AttendancesInfolist;
use App\Filament\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendances;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendancesResource extends Resource
{
    protected static ?string $model = Attendances::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'status';

    public static function form(Schema $schema): Schema
    {
        return AttendancesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendancesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendances::route('/create'),
            'view' => ViewAttendances::route('/{record}'),
            'edit' => EditAttendances::route('/{record}/edit'),
        ];
    }
}
