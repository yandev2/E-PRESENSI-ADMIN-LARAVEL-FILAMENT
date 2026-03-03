<?php

namespace App\Filament\User\Resources\Karyawans;

use App\Filament\User\Resources\Karyawans\Pages\CreateKaryawan;
use App\Filament\User\Resources\Karyawans\Pages\EditKaryawan;
use App\Filament\User\Resources\Karyawans\Pages\ListKaryawans;
use App\Filament\User\Resources\Karyawans\Pages\ViewKaryawan;
use App\Filament\User\Resources\Karyawans\RelationManagers\DokumenRelationManager;
use App\Filament\User\Resources\Karyawans\RelationManagers\LaporanTugasRelationManager;
use App\Filament\User\Resources\Karyawans\RelationManagers\PresensiRelationManager;
use App\Filament\User\Resources\Karyawans\Schemas\KaryawanForm;
use App\Filament\User\Resources\Karyawans\Schemas\KaryawanInfolist;
use App\Filament\User\Resources\Karyawans\Tables\KaryawansTable;
use App\Models\Karyawan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static ?string $navigationLabel = "Karyawan";
    protected static ?string $pluralModelLabel = "Karyawan";
    protected static string | UnitEnum | null $navigationGroup = 'Employee Management';

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'user.email', 'nip'];
    }
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->user->name;
    }
    public static function form(Schema $schema): Schema
    {
        return KaryawanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KaryawanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaryawansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PresensiRelationManager::class,
            DokumenRelationManager::class,
            // LaporanTugasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKaryawans::route('/'),
            'create' => CreateKaryawan::route('/create'),
            'view' => ViewKaryawan::route('/{record}'),
            'edit' => EditKaryawan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('karyawans.*')
            ->addSelect(DB::raw('EXTRACT(YEAR FROM AGE(CURRENT_DATE, karyawans.tanggal_lahir))::int AS usia'));
    }
}
