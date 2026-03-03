<?php

namespace App\Filament\User\Resources\Karyawans;

use App\Filament\User\Resources\Karyawans\Pages\CreateKaryawan;
use App\Filament\User\Resources\Karyawans\Pages\DokumenKaryawan;
use App\Filament\User\Resources\Karyawans\Pages\DokumenKaryawanPage;
use App\Filament\User\Resources\Karyawans\Pages\DokumenKaryawans;
use App\Filament\User\Resources\Karyawans\Pages\EditKaryawan;
use App\Filament\User\Resources\Karyawans\Pages\ListKaryawans;
use App\Filament\User\Resources\Karyawans\Pages\ViewKaryawan;
use App\Filament\User\Resources\Karyawans\Schemas\KaryawanForm;
use App\Filament\User\Resources\Karyawans\Tables\KaryawansTable;
use App\Models\Karyawan;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KaryawanResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = "Karyawan";
    protected static ?string $pluralModelLabel = "Karyawan";
    protected static string | UnitEnum | null $navigationGroup = 'Employee Management';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KaryawanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaryawansTable::configure($table->modifyQueryUsing(
            function ($query) {
                $user = auth()->user();
                $query->where('perusahaan_id', $user->perusahaan_id)
                    ->whereHas('roles', fn($r) => $r->whereIn('name', ['karyawan']));
            }
        ));
    }

    public static function getRelations(): array
    {
        return [
            'lessons' => DokumenKaryawans::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKaryawans::route('/'),
            'create' => CreateKaryawan::route('/create'),
            'view' => ViewKaryawan::route('/{record}'),
            'edit' => EditKaryawan::route('/{record}/edit'),
           'dokumen'=> DokumenKaryawanPage::route('/{record}/dokumen')
          
        ];
    }
}
