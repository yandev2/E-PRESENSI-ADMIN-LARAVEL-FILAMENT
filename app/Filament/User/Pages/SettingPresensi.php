<?php

namespace App\Filament\User\Pages;

use App\Models\PresensiSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;

use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class SettingPresensi extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    protected string $view = 'filament.user.pages.setting-presensi';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?string $navigationLabel = "Presensi Setting";
    protected static ?string $pluralModelLabel = "Presensi Setting";
    public ?array $data = [];
    public ?PresensiSetting $presensiSetting = null;

    public function getRecord(): ?PresensiSetting
    {
        return PresensiSetting::query()
            ->where('perusahaan_id',  auth()->user()->perusahaan->id)
            ->first();
    }
    public function mount(): void
    {
        $this->presensiSetting = PresensiSetting::query()
            ->where('perusahaan_id',  auth()->user()->perusahaan->id)
            ->first();
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    protected function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->presensiSetting)

            ->components([

                Section::make()
                    ->extraAttributes(['class' => 'form-section-custom'])
                    ->heading('Pengaturan Presensi')
                    ->icon(Heroicon::Cog6Tooth)
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 4
                    ])
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('batas_waktu_sebelum_absen_masuk')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('success')
                            ->label('Batas waktu sebelum absen masuk')
                            ->Suffix(' Jam'),
                        TextEntry::make('batas_waktu_sesudah_absen_masuk')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('danger')
                            ->label('Batas waktu sesudah absen masuk')
                            ->Suffix(' Jam'),
                        TextEntry::make('batas_waktu_sebelum_absen_keluar')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('success')
                            ->label('Batas waktu sebelum absen keluar')
                            ->Suffix(' Jam'),
                        TextEntry::make('batas_waktu_sesudah_absen_keluar')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('danger')
                            ->label('Batas waktu sesudah absen keluar')
                            ->Suffix(' Jam'),
                        TextEntry::make('batas_waktu_keterlambatan')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('warning')
                            ->label('Batas waktu keterlambatan')
                            ->Suffix(' Menit'),
                        TextEntry::make('batas_waktu_pengajuan_izin')
                            ->placeholder('-')
                            ->icon(Heroicon::Clock)
                            ->iconColor('info')
                            ->label('Batas waktu pengajuan izin'),
                        TextEntry::make('status_presensi')
                            ->label('Status presensi')
                            ->badge()
                            ->formatStateUsing(fn($state) => $state == 'nonaktif' ? 'Nonaktif' : 'Aktif')
                            ->color(fn(string $state) => $state == 'aktif' ? 'success' : 'danger')

                    ])
            ]);
    }

    protected function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->record($this->getRecord())
            ->components([
                Section::make()
                    ->extraAttributes(['class' => 'form-section-custom'])
                    ->icon(Heroicon::Pencil)
                    ->iconColor('')
                    ->heading('Perbarui Pengaturan Presensi')
                    ->afterHeader([])
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 4
                    ])
                    ->schema([

                        TextInput::make('batas_waktu_sebelum_absen_masuk')
                            ->helperText('batas waktu untuk karyawan dapat melakukan absen masuk sebelum jam shift masuk')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('success')
                            ->suffix('Jam')
                            ->default(1)
                            ->label('Sebelum Masuk')
                            ->numeric()
                            ->required(),
                        TextInput::make('batas_waktu_sesudah_absen_masuk')
                            ->helperText('batas waktu untuk karyawan dapat melakukan absen masuk sesudah jam shift masuk')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('danger')
                            ->suffix('Jam')
                            ->default(1)
                            ->label('Sesudah Masuk')
                            ->numeric()
                            ->required(),

                        TextInput::make('batas_waktu_sebelum_absen_keluar')
                            ->helperText('batas waktu untuk karyawan dapat melakukan absen keluar sebelum jam shift keluar')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('success')
                            ->suffix('Jam')
                            ->default(1)
                            ->label('Sebelum Keluar')
                            ->numeric()
                            ->required(),
                        TextInput::make('batas_waktu_sesudah_absen_keluar')
                            ->helperText('batas waktu untuk karyawan dapat melakukan absen keluar sesudah jam shift keluar')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('danger')
                            ->suffix('Jam')
                            ->default(1)
                            ->label('Sesudah Keluar')
                            ->numeric()
                            ->required(),

                        TextInput::make('batas_waktu_keterlambatan')
                            ->helperText('batas waktu untuk absensi karyawan dinyatakan terlambat')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('warning')
                            ->suffix('Menit')
                            ->default(30)
                            ->label('Keterlambatan')
                            ->numeric()
                            ->required(),

                        TimePicker::make('batas_waktu_pengajuan_izin')
                            ->helperText('batas waktu untuk karyawan dapat melakukan pengajuan izin.')
                            ->prefixIcon(Heroicon::Clock)
                            ->prefixIconColor('info')
                            ->suffix('Jam')
                            ->default('23:59:00')
                            ->afterStateHydrated(function ($state, $set) {
                                if (blank($state)) {
                                    $set('batas_waktu_pengajuan_izin', '23:59:00');
                                }
                            })
                            ->label('Batas Waktu Pengajuan Izin')
                            ->native(false)
                            ->required()
                            ->validationMessages([
                                'required' => 'Batas waktu pengajuan izin wajib diisi.',
                            ]),

                        Select::make('status_presensi')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif'
                            ])
                            ->helperText('jika nonaktif maka semua karyawan tidak dapat melakukan absensi')
                            ->native(false)
                    ])


            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->color('info')
                ->action('save')
        ];
    }

    public function save()
    {
        try {
            $data = $this->form->getState();
            $record = $this->getRecord();

            $record->fill($data);
            $record->save();

            Notification::make()
                ->success()
                ->title('Saved')
                ->send();
        } catch (\Throwable $th) {
            Log::info($th);
            Notification::make()
                ->danger()
                ->title($th->getMessage())
                ->send();
        }
    }
}
