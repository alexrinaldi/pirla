<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Domain\Reservation\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Domain\Shared\Enums\ReservationSource;
use App\Domain\Shared\Enums\ReservationStatus;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Front Desk';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reservation Information')
                    ->schema([
                        Forms\Components\Select::make('hotel_id')
                            ->relationship('hotel', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('confirmation_number')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('status')
                            ->options([
                                ReservationStatus::INQUIRY->value => ReservationStatus::INQUIRY->label(),
                                ReservationStatus::OPTION->value => ReservationStatus::OPTION->label(),
                                ReservationStatus::CONFIRMED->value => ReservationStatus::CONFIRMED->label(),
                                ReservationStatus::CHECKED_IN->value => ReservationStatus::CHECKED_IN->label(),
                                ReservationStatus::CHECKED_OUT->value => ReservationStatus::CHECKED_OUT->label(),
                                ReservationStatus::CANCELLED->value => ReservationStatus::CANCELLED->label(),
                                ReservationStatus::NO_SHOW->value => ReservationStatus::NO_SHOW->label(),
                            ])
                            ->required()
                            ->default(ReservationStatus::CONFIRMED->value),
                        Forms\Components\Select::make('source')
                            ->options([
                                ReservationSource::DIRECT->value => ReservationSource::DIRECT->label(),
                                ReservationSource::OTA->value => ReservationSource::OTA->label(),
                                ReservationSource::PHONE->value => ReservationSource::PHONE->label(),
                                ReservationSource::EMAIL->value => ReservationSource::EMAIL->label(),
                            ])
                            ->required()
                            ->default(ReservationSource::DIRECT->value),
                    ])->columns(2),
                Forms\Components\Section::make('Dates & Occupancy')
                    ->schema([
                        Forms\Components\DatePicker::make('check_in_date')
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('check_out_date')
                            ->required()
                            ->native(false)
                            ->after('check_in_date'),
                        Forms\Components\TextInput::make('adults')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        Forms\Components\TextInput::make('children')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])->columns(2),
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('special_requests')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('confirmation_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (ReservationStatus $state): string => $state->color())
                    ->formatStateUsing(fn (ReservationStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('source')
                    ->formatStateUsing(fn (ReservationSource $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adults')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('children')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        ReservationStatus::INQUIRY->value => ReservationStatus::INQUIRY->label(),
                        ReservationStatus::OPTION->value => ReservationStatus::OPTION->label(),
                        ReservationStatus::CONFIRMED->value => ReservationStatus::CONFIRMED->label(),
                        ReservationStatus::CHECKED_IN->value => ReservationStatus::CHECKED_IN->label(),
                        ReservationStatus::CHECKED_OUT->value => ReservationStatus::CHECKED_OUT->label(),
                        ReservationStatus::CANCELLED->value => ReservationStatus::CANCELLED->label(),
                        ReservationStatus::NO_SHOW->value => ReservationStatus::NO_SHOW->label(),
                    ]),
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        ReservationSource::DIRECT->value => ReservationSource::DIRECT->label(),
                        ReservationSource::OTA->value => ReservationSource::OTA->label(),
                        ReservationSource::PHONE->value => ReservationSource::PHONE->label(),
                        ReservationSource::EMAIL->value => ReservationSource::EMAIL->label(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
