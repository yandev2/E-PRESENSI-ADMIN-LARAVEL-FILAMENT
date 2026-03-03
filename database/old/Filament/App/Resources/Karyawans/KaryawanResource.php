<?php

namespace App\Filament\App\Resources\Karyawans;

use App\Filament\App\Resources\Karyawans\Pages\CreateKaryawan;
use App\Filament\App\Resources\Karyawans\Pages\EditKaryawan;
use App\Filament\App\Resources\Karyawans\Pages\ListKaryawans;
use App\Filament\App\Resources\Karyawans\Pages\ViewKaryawan;
use App\Filament\App\Resources\Karyawans\Schemas\KaryawanForm;
use App\Filament\App\Resources\Karyawans\Schemas\KaryawanInfolist;
use App\Filament\App\Resources\Karyawans\Tables\KaryawansTable;
use App\Models\UserDetails;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KaryawanResource extends Resource
{
    protected static ?string $model = UserDetails::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;
    protected static ?string $navigationLabel = "Karyawan";
    protected static ?string $pluralModelLabel = "Karyawan";
    protected static string | UnitEnum | null $navigationGroup = 'Employee Management';
    protected static ?string $recordTitleAttribute = 'name';
    public static function form(Schema $schema): Schema
    {
        return KaryawanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaryawansTable::configure($table->modifyQueryUsing(
            function ($query) {
                $user = auth()->user();
                $query->where('company_id', $user->company_id)
                    ->whereHas('user', fn($q) => $q->whereHas('roles', fn($r) => $r->whereIn('name', ['employee'])));
            }
        ));
    }

    public static function infolist(Schema $schema): Schema
    {
        return KaryawanInfolist::configure($schema);
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
            'index' => ListKaryawans::route('/'),
            'create' => CreateKaryawan::route('/create'),
            'view' => ViewKaryawan::route('/{record}'),
            'edit' => EditKaryawan::route('/{record}/edit'),
        ];
    }
}
