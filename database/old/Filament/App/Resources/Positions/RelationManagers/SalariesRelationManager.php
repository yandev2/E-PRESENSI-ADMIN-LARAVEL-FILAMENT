<?php

namespace App\Filament\App\Resources\Positions\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'salaries';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 2,
                '2xl' => 3
            ])
            ->components([
                TextInput::make('gaji_bulanan')
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))
                    ->required(),
                TextInput::make('gaji_lembur')
                    ->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))
                    ->required(),
                TextInput::make('hari_kerja')
                    ->required()
                    ->numeric(),
                TextInput::make('potongan_alpha_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('potongan_izin_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('potongan_sakit_persen')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('effective_from')
                    ->prefixIcon(Heroicon::Calendar)
                    ->displayFormat('d-M-Y')
                    ->format('d-M-Y')
                    ->default(now())
                    ->native(false)
                    ->hidden(fn($record) => $record != null),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('History Salary')
            ->recordTitleAttribute('gaji_bulanan')
            ->columns([
                TextColumn::make('gaji_bulanan')
                    ->prefix('Rp.'),
                TextColumn::make('gaji_lembur')
                    ->prefix('Rp.'),
                TextColumn::make('hari_kerja')
                    ->suffix(' H')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('potongan_alpha_persen')
                    ->label('Alpha')
                    ->suffix(' %')
                    ->badge()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('potongan_izin_persen')
                    ->label('Izin')
                    ->suffix(' %')
                     ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('potongan_sakit_persen')
                    ->label('Sakit')
                    ->suffix(' %')
                     ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('effective_from')
                    ->date()
                    ->Label('Aktif dari')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('effective_to')
                    ->date()
                    ->sortable()
                    ->Label('Sampai')
                    ->default(Carbon::now()->format('d-M-Y'))
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('status')
                    ->icon(fn($state) => match ($state) {
                        'active'   => Heroicon::CheckCircle,
                        'inactive' => Heroicon::XCircle,
                    })
                    ->color(fn($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Salary')
                    ->icon(Heroicon::Plus)
                    ->modalIcon(Heroicon::Plus)
                    ->color('success')
                    ->modalAlignment(Alignment::Left)
                    ->modalHeading('Update New Salary Position')
                    ->stickyModalHeader()
                    ->authorize(true)
                    ->successNotification(null)
                    ->after(function ($record, $livewire) {
                        try {
                            DB::beginTransaction();
                            $oldSalary = $livewire->ownerRecord->salaries->skip(1)->first();

                            if ($oldSalary) {
                                $oldSalary->update([
                                    "status" => "inactive",
                                    'effective_to' => Carbon::parse($record->effective_from),
                                ]);
                            }
                            DB::commit();
                            Notification::make()
                                ->title('Sukses')
                                ->body('new position successfully added')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Log::info($th);
                            DB::rollBack();

                            if ($record?->exists) {
                                $record->delete();
                            }
                            Notification::make()
                                ->title('Ops')
                                ->body('an error occurred while creating a new position')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->badge()
                    ->icon(Heroicon::Pencil)
                    ->modalIcon(Heroicon::Pencil)
                    ->color('success')
                    ->modalAlignment(Alignment::Left)
                    ->modalHeading('Edit Salary Position')
                    ->stickyModalHeader()
                    ->authorize(true)
                    ->hidden(fn($record) => $record->status === 'inactive'),
                DeleteAction::make()
                     ->badge()
                    ->authorize(true)
                    ->hidden(fn($record) => $record->status === 'active'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(true),
                ]),
            ]);
    }
}
