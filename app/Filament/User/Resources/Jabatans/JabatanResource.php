<?php

namespace App\Filament\User\Resources\Jabatans;

use App\Filament\User\Resources\Jabatans\Pages\CreateJabatan;
use App\Filament\User\Resources\Jabatans\Pages\EditJabatan;
use App\Filament\User\Resources\Jabatans\Pages\ListJabatans;
use App\Filament\User\Resources\Jabatans\Pages\ViewJabatan;
use App\Filament\User\Resources\Jabatans\RelationManagers\GajiRelationManager;
use App\Filament\User\Resources\Jabatans\Schemas\JabatanForm;
use App\Filament\User\Resources\Jabatans\Schemas\JabatanInfolist;
use App\Filament\User\Resources\Jabatans\Tables\JabatansTable;
use App\Models\Jabatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;
    protected static ?string $navigationLabel = "Jabatan";
    protected static ?string $pluralModelLabel = "Jabatan";
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_jabatan', 'kode_jabatan'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return  $record->nama_jabatan;
    }

    public static function form(Schema $schema): Schema
    {
        return JabatanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JabatanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JabatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GajiRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJabatans::route('/'),
            'create' => CreateJabatan::route('/create'),
            'view' => ViewJabatan::route('/{record}'),
            'edit' => EditJabatan::route('/{record}/edit'),
        ];
    }
}
