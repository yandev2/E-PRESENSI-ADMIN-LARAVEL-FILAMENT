<?php

namespace App\Filament\User\Resources\Karyawans\Pages;

use App\Filament\User\Resources\Karyawans\KaryawanResource;
use App\Models\DokumenKaryawan;
use App\Models\Karyawan;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokumenKaryawanPage extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms;

    protected static string $resource = KaryawanResource::class;

    protected string $view = 'filament.user.resources.karyawans.pages.dokumen-karyawan-page';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getTableQuery()
    {

        $id_karyawan = Karyawan::where('user_id', $this->record->id)->value('id');
        $data = DokumenKaryawan::query()
            ->where('karyawan_id', $id_karyawan)
            ->latest();
        return $data;
    }


    public function table(Table $table)
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Tambah Dokumen')
                    ->button()
                    ->color('primary')
                    ->modalIcon(Heroicon::Plus)
                    ->modalAlignment(Alignment::Left)
                    ->modalHeading('TAMBAH DOKUMEN')
                    ->modalDescription('Tambahkan data dokumen pada karyawan ini')
                    ->stickyModalHeader()
                    ->successNotification(null)
                    ->action(fn(array $data, Action $action) =>   $this->saveData($data, $action))
                    ->schema(FormDokumenKaryawan::getComponents())
            ])
            ->recordActions($this->getRecordActions($this->record));
    }


    protected function getRecordActions($record): array|ActionGroup
    {
        return [
            Action::make('delete')
                ->button()
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn($record, Action $action) => $this->deleteData($record, $action)),

            Action::make('edit')
                ->button()
                ->color('primary')
                ->modalIcon(Heroicon::Pencil)
                ->modalAlignment(Alignment::Left)
                ->modalHeading('EDIT DOKUMEN')
                ->modalDescription('Edit data dokumen pada karyawan ini')
                ->stickyModalHeader()
                ->successNotification(null)
                ->action(fn(array $data, Action $action) =>   $this->saveData($data, $action))
                ->schema(FormDokumenKaryawan::getComponents())
        ];
    }

    protected function deleteData($data, Action $action)
    {
        try {
            DokumenKaryawan::where('id', $data->id)->delete();
            $action->successNotification(
                Notification::make()
                    ->success()
                    ->title('Okay')
                    ->body('data dokumen berhasil dihapus')
            );
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            $action->successNotification(
                Notification::make()
                    ->success()
                    ->title('Ups')
                    ->body('terjadi kesalahan saat menghapus data')
            );
        }
    }

    protected function saveData(array $data, Action $action)
    {
        try {
            $karyawan = Karyawan::firstWhere('user_id', $this->record->id);

            $karyawan->dokumen()->create([
                'karyawan_id' => $karyawan->id,
                'perusahaan_id' => $this->record->perusahaan_id,
                'jenis_dokumen' => $data['jenis_dokumen'],
                'nama_dokumen' => $data['nama_dokumen'],
                'tanggal_terbit' => $data['tanggal_terbit'] ?? null,
                'tanggal_expired' => $data['tanggal_expired'] ?? null,
                'deskripsi' => $data['deskripsi'] ?? null,
                'file' => $data['file'] ?? null,
            ]);

            $action->successNotification(
                Notification::make()
                    ->success()
                    ->title('Okay')
                    ->body('data dokumen berhasil ditambahkan')
            );

            //
        } catch (\Throwable $th) {

            if ($data['file'] && Storage::disk('public')->exists($data['file'])) {
                Storage::disk('public')->delete($data['file']);
            }

            Log::info($th->getMessage());

            $action->failureNotification(
                Notification::make()
                    ->success()
                    ->title('Ups')
                    ->body('terjadi kesalahan saat menambahkan data')
            );
        }
    }

     protected function editData(array $data, Action $action)
    {}
}

class FormDokumenKaryawan
{
    public static function getComponents(): array
    {
        return [
            Grid::make()
                ->columns([
                    'sm' => 1,
                    'md' => 1,
                    'lg' => 1,
                    'xl' => 1,
                    '2xl' => 4
                ])
                ->schema([
                    Select::make('jenis_dokumen')
                        ->label('Jenis Dokumen')
                        ->searchable()
                        ->placeholder('')
                        ->native(false)
                        ->options([
                            'ktp' => 'KTP',
                            'kartu_keluarga' => 'Kartu Keluarga',
                            'ijazah' => 'Ijazah',
                            'skck' => 'SKCK',
                            'npwp' => 'NPWP',
                            'sim' => 'SIM',
                            'other' => 'Other',
                        ])
                        ->required(),
                    TextInput::make('nama_dokumen')->required(),
                    DatePicker::make('tanggal_terbit')
                        ->label('Tanggal Terbit')
                        ->prefixIcon(Heroicon::Calendar)
                        ->displayFormat('d-M-Y')
                        ->format('d-M-Y')
                        ->native(false),
                    DatePicker::make('tanggal_expired')
                        ->label('Tanggal Expired')
                        ->prefixIcon(Heroicon::Calendar)
                        ->displayFormat('d-M-Y')
                        ->format('d-M-Y')
                        ->native(false),
                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->columnSpan(2),
                    FileUpload::make('file')
                        ->label('Upload File')
                        ->disk('public')
                        ->columnSpan(2)
                        ->preserveFilenames()
                        ->directory(function () {
                            $path = auth()->user()->perusahaan->nama_perusahaan . '/karyawan/dokumen';
                            return $path;
                        })
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->required(),
                ])
        ];
    }
}
