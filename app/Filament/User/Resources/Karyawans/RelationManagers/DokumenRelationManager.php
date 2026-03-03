<?php

namespace App\Filament\User\Resources\Karyawans\RelationManagers;

use App\Filament\User\Componen\Button\AddButtonComponen;
use App\Filament\User\Resources\Karyawans\Pages\EditKaryawan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Filament\User\Componen\Button\DeleteButtonComponen;
use App\Filament\User\Componen\Button\DownloadButtonComponen;
use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Filament\User\Componen\Button\MediaPriviewButtonComponen;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Storage;

class DokumenRelationManager extends RelationManager
{
    protected static string $relationship = 'dokumen';

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Dokumen karyawan')
            ->badge($ownerRecord->dokumen()->count())
            ->badgeColor('info')
            ->badgeTooltip('dokumen pribadi karyawan')
            ->icon('heroicon-m-document-text');
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::dokumenKaryawanForm()
            ]);
    }

    public static function dokumenKaryawanForm()
    {
        return Grid::make()
            ->columnSpanFull()
            ->schema([
                Select::make('jenis_dokumen')
                    ->label('Jenis Dokumen')
                    ->required()
                    ->placeholder('')
                    ->searchable()
                    ->native(false)
                    ->options([
                        'ktp' => 'KTP',
                        'kartu_keluarga' => 'Kartu Keluarga',
                        'ijazah' => 'Ijazah',
                        'skck' => 'SKCK',
                        'npwp' => 'NPWP',
                        'sim' => 'SIM',
                        'other' => 'Other'
                    ]),
                TextInput::make('nama_dokumen')
                    ->required(),
                DatePicker::make('tanggal_terbit')
                    ->prefixIcon(Heroicon::Calendar)
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->native(false),
                DatePicker::make('tanggal_expired')
                    ->prefixIcon(Heroicon::Calendar)
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->native(false),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('file')
                    ->label('Upload File')
                    ->disk('public')
                    ->downloadable()
                    ->directory(function () {
                        $path =  auth()->user()->perusahaan_id . '/perusahaan/karyawan/dokumen';
                        return $path;
                    })
                    ->columnSpanFull()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->required(),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_dokumen')
            ->heading('')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('TIDAK ADA DOKUMEN')
            ->emptyStateDescription('tidak ada dokumen karyawan ditambahkan')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->paginationMode(PaginationMode::Default)
            ->columns(static::dokumenKaryawanColumn())
            ->headerActions([
                AddButtonComponen::make()
                    ->authorize(true)
                    ->modalIcon(Heroicon::Plus)
                    ->modalWidth(Width::FitContent)
                    ->modalAlignment(Alignment::Left)
                    ->modalHeading('TAMBAH DOKUMEN')
                    ->modalDescription('Tambahkan data dokumen karyawan')
                    ->stickyModalHeader()
            ])
            ->recordActions([
                EditButtonComponen::make(),
                DeleteButtonComponen::make(),
                DownloadButtonComponen::make(),
                MediaPriviewButtonComponen::make()
                    ->modalHeading(fn($record) => $record->nama_dokumen),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->authorize(true),
            ]);
    }

    public static function dokumenKaryawanColumn()
    {
        return [
            TextColumn::make('index')
                ->label('No. ')
                ->width('sm')
                ->rowIndex(),
            TextColumn::make('jenis_dokumen')
                ->badge()
                ->color('info')
                ->searchable()
                ->placeholder('-'),
            TextColumn::make('nama_dokumen')
                ->searchable()
                ->placeholder('-'),
            TextColumn::make('deskripsi')
                ->searchable()
                ->placeholder('-'),
            TextColumn::make('tanggal_terbit')
                ->searchable()
                ->date('d-M-Y')
                ->placeholder('-'),
            TextColumn::make('tanggal_expired')
                ->searchable()
                ->date('d-M-Y')
                ->placeholder('-'),
        ];
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return $pageClass != EditKaryawan::class;
    }
}
