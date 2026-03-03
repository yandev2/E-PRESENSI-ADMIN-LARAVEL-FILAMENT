<?php

namespace App\Filament\User\Resources\Karyawans\RelationManagers;

use App\Filament\App\Resources\Karyawans\Pages\EditKaryawan;
use App\Filament\User\Componen\Button\AssociateButtonComponen;
use App\Filament\User\Componen\TugasKaryawan\TugaskaryawanColumnComponen;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\DownloadButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\MediaPriviewButtonComponen;
use App\Filament\User\Componen\Button\ViewButtonComponen;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;

class LaporanTugasRelationManager extends RelationManager
{
    protected static string $relationship = 'tugas';

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Laporan Tugas')
            ->badge($ownerRecord->laporanTugas()->count())
            ->badgeColor('info')
            ->badgeTooltip('laporan tugas dari karyawan')
            ->icon(Heroicon::RectangleStack);
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('perusahaan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('aprove_id')
                    ->numeric(),
                TextInput::make('tugas_id')
                    ->required()
                    ->numeric(),
                Textarea::make('status')
                    ->default('pending')
                    ->columnSpanFull(),
                Textarea::make('laporan')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('tanggal_approve'),
                DatePicker::make('tanggal_diserahkan'),
                TextInput::make('file')
                    ->required(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('perusahaan_id')
                    ->numeric(),
                TextEntry::make('aprove_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tugas_id')
                    ->numeric(),
                TextEntry::make('status')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('laporan')
                    ->columnSpanFull(),
                TextEntry::make('tanggal_approve')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('tanggal_diserahkan')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('file'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('laporan')
            ->heading('')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA TUGAS')
            ->emptyStateDescription('tidak ada tugas karyawan ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::tugasKaryawanColumn())
            ->headerActions([
                AssociateButtonComponen::make()
                    ->label('Tambahkan Tugas')
                    ->modalHeading('TAMBAH TUGAS')
                    ->modalDescription('Tambahkan tugas untuk karyawan')
                    ->recordSelectSearchColumns(['nama_tugas', 'priority', 'tanggal_dikeluarkan'])
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        return $query->where('status', 'open')->orderByDesc('tanggal_dikeluarkan');
                    })
            ])
            ->recordActions([
                DownloadButtonComponen::make(),
                MediaPriviewButtonComponen::make(),
                ViewButtonComponen::make(),
                EditButtonComponen::make(),
                DeleteButtonComponen::make(),
                DissociateAction::make(),
            ])
            ->toolbarActions([
                DissociateBulkAction::make(),
                DeleteBulkAction::make(),
            ]);
    }

    public static function tugasKaryawanColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('nama_tugas')
                ->label('Tugas')
                ->placeholder('-'),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color('info')
                ->placeholder('-'),
            TextColumn::make('laporan')
                ->placeholder('-'),
            TextColumn::make('tanggal_approve')
                ->date('d-M-Y')
                ->placeholder('-'),
            TextColumn::make('tanggal_diserahkan')
                ->date('d-M-Y')
                ->placeholder('-'),
        ];
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return $pageClass != EditKaryawan::class;
    }
}
