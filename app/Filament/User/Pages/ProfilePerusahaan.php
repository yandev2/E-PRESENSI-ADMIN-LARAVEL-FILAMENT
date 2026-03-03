<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Componen\Button\EditButtonComponen;
use App\Models\Perusahaan;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;

class ProfilePerusahaan extends Page implements HasForms, HasInfolists
{

    use InteractsWithForms, InteractsWithInfolists;
    protected string $view = 'filament.user.pages.profile-perusahaan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;
    protected static ?string $navigationLabel = "Perusahaan";
    protected static ?string $pluralModelLabel = "Perusahaan";
    public ?array $data = [];
    public ?Perusahaan $perusahaan = null;


    public function getRecord(): ?Perusahaan
    {
        return Perusahaan::query()
            ->where('id',  auth()->user()->perusahaan->id)
            ->first();
    }
    public function mount(): void
    {
        $user = auth()->user();
        $this->perusahaan = Perusahaan::findOrFail($user->perusahaan_id);
        $this->form->fill($this->getRecord()?->attributesToArray());
    }


    protected function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->perusahaan)
            ->components([
                Section::make()
                    ->extraAttributes(['class' => 'form-section-custom'])
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 3,
                        'xl' => 5,
                        '2xl' => 5
                    ])
                    ->schema([
                        ImageEntry::make('logo')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->imageSize('100%')
                            ->disk('public'),
                        Grid::make()
                            ->columnSpan(4)
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2
                            ])
                            ->schema([
                                TextEntry::make('nama_perusahaan')
                                    ->icon(Heroicon::BuildingOffice)
                                    ->iconColor('info')
                                    ->label('Nama Perusahaan'),
                                TextEntry::make('npwp')
                                    ->placeholder('-')
                                    ->label('NPWP'),
                                TextEntry::make('kontak')
                                    ->icon(Heroicon::Phone)
                                    ->placeholder('-')
                                    ->iconColor('info')
                                    ->label('Kontak'),
                                TextEntry::make('email')
                                    ->icon(Heroicon::Envelope)
                                    ->placeholder('-')
                                    ->iconColor('info')
                                    ->label('Email'),
                                TextEntry::make('deskripsi')
                                    ->placeholder('-')
                                    ->label('Deskripsi'),
                                TextEntry::make('alamat')
                                    ->icon(Heroicon::Map)
                                    ->iconColor('info')
                                    ->placeholder('-')
                                    ->label('Alamat'),
                                TextEntry::make('lokasi')
                                    ->icon(Heroicon::MapPin)
                                    ->iconColor('info')
                                    ->placeholder('-')
                                    ->label('Lokasi'),
                                TextEntry::make('site')
                                    ->icon(Heroicon::GlobeAlt)
                                    ->iconColor('info')
                                    ->placeholder('-')
                                    ->label('Site')
                            ])
                    ]),
            ]);
    }
    protected function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->record($this->getRecord())
            ->components([
                Form::make([
                    Section::make()
                        ->extraAttributes(['class' => 'form-section-custom'])
                        ->icon(Heroicon::Pencil)
                        ->iconColor('')
                        ->heading('Perbarui Data Perusahaan')
                        ->afterHeader([])
                        ->columnSpanFull()
                        ->columns([
                            'sm' => 1,
                            'md' => 1,
                            'lg' => 1,
                            'xl' => 3,
                            '2xl' => 3
                        ])
                        ->schema([
                            FileUpload::make('logo')
                                ->label('Foto')
                                ->columnSpan(1)
                                ->hiddenLabel()
                                ->disk('public')
                                ->maxSize(10240)
                                ->openable()
                                ->downloadable()
                                ->alignCenter()
                                ->panelLayout('integrated')
                                ->imageEditorMode(2)
                                ->loadingIndicatorPosition('center')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadButtonPosition('center')
                                ->uploadProgressIndicatorPosition('center')
                                ->directory(fn() => auth()->user()->perusahaan_id . '/perusahaan/profile')
                                ->image()
                                ->panelAspectRatio('1:1'),
                            Grid::make()
                                ->columnSpan(2)
                                ->columns([
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 2,
                                    'xl' => 4,
                                    '2xl' => 4
                                ])
                                ->schema([
                                    TextInput::make('nama_perusahaan')
                                        ->prefixIcon(Heroicon::BuildingOffice)
                                        ->prefixIconColor('info')
                                        ->label('Nama')
                                        ->required(),
                                    TextInput::make('npwp')
                                        ->prefixIcon(Heroicon::CheckBadge)
                                        ->prefixIconColor('info')
                                        ->label('npwp')
                                        ->required(),
                                    TextInput::make('lokasi')
                                        ->prefixIcon(Heroicon::MapPin)
                                        ->prefixIconColor('info')
                                        ->label('Lokasi')
                                        ->required(),
                                    TextInput::make('site')
                                        ->prefixIcon(Heroicon::GlobeAlt)
                                        ->prefixIconColor('info')
                                        ->helperText('opsional jika anda mempunyai situs resmi perusahaan')
                                        ->label('Site'),
                                    Textarea::make('deskripsi')
                                        ->rows(4)
                                        ->columnSpan(2)
                                        ->label('Deskripsi'),
                                    Textarea::make('alamat')
                                        ->rows(4)
                                        ->columnSpan(2)
                                        ->label('Alamat'),
                                    TextInput::make('kontak')
                                        ->prefixIcon(Heroicon::Phone)
                                        ->prefixIconColor('info')
                                        ->columnSpan(2)
                                        ->label('kontak'),
                                    TextInput::make('email')
                                        ->prefixIcon(Heroicon::Envelope)
                                        ->prefixIconColor('info')
                                        ->columnSpan(2)
                                        ->label('Email')
                                        ->required(),
                                ]),

                        ]),

                    Section::make()
                        ->extraAttributes(['class' => 'form-section-custom'])
                        ->heading('Kantor Perusahaan')
                        ->icon(Heroicon::OutlinedUserGroup)
                        ->columnSpanFull()
                        ->schema([
                            Repeater::make('kantor')
                                ->relationship('kantor')
                                ->hiddenLabel()
                                ->addActionLabel('Tambah')
                                ->columnSpanFull()
                                ->addActionAlignment(Alignment::Right)
                                ->table([
                                    TableColumn::make('nama_kantor')->markAsRequired(),
                                    TableColumn::make('radius')->markAsRequired(),
                                    TableColumn::make('lokasi')->markAsRequired(),
                                ])
                                ->schema([
                                    TextInput::make('nama_kantor')
                                        ->label('Nama')
                                        ->required()
                                        ->helperText('nama kantor'),
                                    TextInput::make('radius')
                                        ->label('Nama')
                                        ->numeric()
                                        ->suffix("Meter")
                                        ->required()
                                        ->helperText('radius karyawan dapat melakukan presensi'),
                                    TextInput::make('lokasi')
                                        ->label('Lokasi')
                                        ->required()
                                        ->prefixIcon(Heroicon::MapPin)
                                        ->prefixIconColor('info')
                                        ->helperText('ini adalah titik lokasi dimana karyawan bisa melakukan absensi')
                                ])
                        ])
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

            Notification::make()
                ->danger()
                ->title('Ups')
                ->send();

            $this->halt();
        }
    }
}
