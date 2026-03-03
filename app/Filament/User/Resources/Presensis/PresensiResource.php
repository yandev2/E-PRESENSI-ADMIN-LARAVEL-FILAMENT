<?php
namespace App\Filament\User\Resources\Presensis;

use App\Filament\User\Resources\Presensis\Pages\CreatePresensi;
use App\Filament\User\Resources\Presensis\Pages\EditPresensi;
use App\Filament\User\Resources\Presensis\Pages\ListPresensis;
use App\Filament\User\Resources\Presensis\Pages\ViewPresensi;
use App\Filament\User\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\User\Resources\Presensis\Schemas\PresensiInfolist;
use App\Filament\User\Resources\Presensis\Tables\PresensisTable;
use App\Models\Presensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::FingerPrint;
    protected static ?string $navigationLabel = "Presensi";
    protected static ?string $pluralModelLabel = "Presensi";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    public static function getGloballySearchableAttributes(): array
    {
        return ['karyawan.user.name', 'karyawan.nip', 'tanggal'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return 'presensi ' . $record->karyawan->user->name . ' ' . $record->tanggal;
    }

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PresensiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensisTable::configure($table);
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
            'index' => ListPresensis::route('/'),
            'create' => CreatePresensi::route('/create'),
            'view' => ViewPresensi::route('/{record}'),
            'edit' => EditPresensi::route('/{record}/edit'),
        ];
    }
}
