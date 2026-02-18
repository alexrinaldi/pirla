<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Domain\Inventory\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Domain\Shared\Enums\RoomStatus;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Setup';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Information')
                    ->schema([
                        Forms\Components\Select::make('hotel_id')
                            ->relationship('hotel', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('room_type_id')
                            ->relationship('roomType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('room_number')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('floor')
                            ->numeric(),
                        Forms\Components\Select::make('status')
                            ->options([
                                RoomStatus::AVAILABLE->value => RoomStatus::AVAILABLE->label(),
                                RoomStatus::OCCUPIED->value => RoomStatus::OCCUPIED->label(),
                                RoomStatus::MAINTENANCE->value => RoomStatus::MAINTENANCE->label(),
                                RoomStatus::OUT_OF_ORDER->value => RoomStatus::OUT_OF_ORDER->label(),
                            ])
                            ->required()
                            ->default(RoomStatus::AVAILABLE->value),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roomType.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (RoomStatus $state): string => $state->color())
                    ->formatStateUsing(fn (RoomStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        RoomStatus::AVAILABLE->value => RoomStatus::AVAILABLE->label(),
                        RoomStatus::OCCUPIED->value => RoomStatus::OCCUPIED->label(),
                        RoomStatus::MAINTENANCE->value => RoomStatus::MAINTENANCE->label(),
                        RoomStatus::OUT_OF_ORDER->value => RoomStatus::OUT_OF_ORDER->label(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
