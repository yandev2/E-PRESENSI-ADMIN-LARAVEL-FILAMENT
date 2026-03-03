<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements HasForms, HasInfolists
{

    use InteractsWithForms, InteractsWithInfolists;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $pluralModelLabel = "Profile";
    protected string $view = 'filament.user.pages.profile';

    public ?array $data = [];
    public ?User $user = null;

    public function getRecord(): ?User
    {
        return auth()->user();
    }
    public function mount(): void
    {
        $this->user = auth()->user();
        $this->form->fill($this->getRecord()?->attributesToArray());
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
    protected function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->record($this->getRecord())
            ->components([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                        '2xl' => 2
                    ])
                    ->schema([
                        Section::make()
                            ->heading('Edit Profile')
                            ->extraAttributes(['class' => 'form-section-custom'])
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('avatar')
                                    ->disk('public')
                                    ->directory(fn() => auth()->user()->perusahaan_id . '/perusahaan/karyawan/profile')
                                    ->maxSize(10240)
                                    ->openable()
                                    ->downloadable()
                                    ->panelLayout('integrated')
                                    ->imageEditorMode(2)
                                    ->image()
                                    ->panelAspectRatio('1:1')
                                    ->alignCenter()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->inlineLabel(),
                                TextInput::make('name')
                                    ->required()
                                    ->inlineLabel()
                                    ->prefixIconColor('info')
                                    ->prefixIcon(Heroicon::UserCircle),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->inlineLabel()
                                    ->prefixIconColor('info')
                                    ->prefixIcon(Heroicon::Envelope),
                                TextInput::make('c')
                                    ->password()
                                    ->label('Password Baru')
                                    ->nullable()
                                    ->inlineLabel()
                                    ->revealable(),
                            ]),
                    ])
            ]);
    }

    public function save()
    {
        try {
            $data = $this->form->getState();
            $record = $this->getRecord();


            if (!empty($data['c'])) {
                $data['c'] = Hash::make($data['c']);
                $data['password'] =  $data['c'];
            }

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
