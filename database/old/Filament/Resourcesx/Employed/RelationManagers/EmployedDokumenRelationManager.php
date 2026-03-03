<?php

namespace App\Filament\Resources\Employed\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

class EmployedDokumenRelationManager extends RelationManager
{
    protected static string $relationship = 'userDokumen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis_dokumen')
                    ->native(false)
                    ->placeholder('')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options([
                        'KTP' => '1. KTP',
                        'KK' => '2. KK',
                        'NPWP' => '3. NPWP',
                        'BPJS Kesehatan' => '4. BPJS Kesehatan',
                    ]),
                TextInput::make('judul')
                    ->required(),
                DatePicker::make('tanggal_terbit')
                    ->format('d M Y')
                    ->displayFormat('d M Y')
                    ->prefixIcon('heroicon-m-calendar')
                    ->native(false)
                    ->label('Tanggal Terbit')
                    ->nullable(),
                DatePicker::make('tanggal_expired')
                    ->format('d M Y')
                    ->displayFormat('d M Y')
                    ->prefixIcon('heroicon-m-calendar')
                    ->native(false)
                    ->label('Tanggal Expired')
                    ->nullable(),
                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->rows(3),
                FileUpload::make('file')
                    ->label('File')
                    ->disk('public')
                    ->directory('user/dokumen')
                    ->columnSpanFull()
                    ->required()
                    ->downloadable()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->getUploadedFileNameForStorageUsing(function ($file, callable $get) {
                        try {
                            $nama_employed = optional($this->ownerRecord)->name ?? 'Nan';
                            $jenis     = $get('jenis_dokumen') ?? 'dokumen';
                            $now       = now()->format('YmdHis');

                            $filename = strtolower(
                                preg_replace('/[^A-Za-z0-9\-]/', '_', "{$nama_employed}-{$jenis}-{$now}")
                            ) . '.' . $file->getClientOriginalExtension();

                            \Log::info("FileUpload generate name: {$filename}");
                            return $filename;
                        } catch (\Throwable $e) {
                            \Log::error("FileUpload error: " . $e->getMessage());
                            throw $e; // biar error kelihatan
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('belum ada data ditambahkan')
            ->defaultPaginationPageOption('5')
            ->heading('')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('jenis_dokumen'),
                TextColumn::make('judul'),
                TextColumn::make('deskripsi'),
                TextColumn::make('tanggal_terbit')
                    ->date('d M Y')
                    ->badge()
                    ->color('success'),
                TextColumn::make('tanggal_expired')
                    ->date('d M Y')
                    ->badge()
                    ->color('danger'),
            ])
            ->recordActions([
                Action::make('download')
                    ->size('sm')
                    ->color('success')
                    ->button()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => asset('storage/' . $record->file), shouldOpenInNewTab: true)
                    ->visible(function ($record) {
                        $path = $record->file ?? null;
                        if (! $path) return false;
                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        return ! in_array($extension, ['pdf', 'jpg', 'jpeg', 'png']);
                    }),
                MediaAction::make('Preview')
                    ->label('Preview')
                    ->size('sm')
                    ->color('success')
                    ->button()
                    ->modalWidth('full')
                    ->modalIcon(Heroicon::Eye)
                    ->icon(Heroicon::Eye)
                    ->modalHeading(fn($record) => $record->jenis_dokumen)
                    ->media(fn($record) => str_replace(' ', '%20', Storage::url($record->file)))
                    ->visible(function ($record) {
                        $path = $record->file ?? null;
                        if (! $path) return false;
                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        return in_array($extension, ['pdf', 'jpg', 'jpeg', 'png']);
                    }),
                EditAction::make()
                    ->authorize(true)
                    ->button()
                    ->modalHeading('Edit Employed Document')
                    ->modalIcon(Heroicon::DocumentText),
                DeleteAction::make()
                    ->authorize(true)
                    ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Document')
                    ->color('info')
                    ->modalHeading('Add Employed Document')
                    ->modalIcon(Heroicon::DocumentText)
                    ->icon(Heroicon::DocumentText),
            ]);
    }
}
