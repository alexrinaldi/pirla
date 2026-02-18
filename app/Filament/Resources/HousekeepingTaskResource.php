<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Operations\Models\HousekeepingTask;
use App\Domain\Shared\Enums\HousekeepingTaskType;
use App\Domain\Shared\Enums\HousekeepingTaskStatus;
use App\Filament\Resources\HousekeepingTaskResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HousekeepingTaskResource extends Resource
{
    protected static ?string $model = HousekeepingTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\Select::make('room_id')
                            ->relationship('room', 'room_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('type')
                            ->options(HousekeepingTaskType::class)
                            ->required()
                            ->enum(HousekeepingTaskType::class),
                        Forms\Components\Select::make('status')
                            ->options(HousekeepingTaskStatus::class)
                            ->required()
                            ->default(HousekeepingTaskStatus::OPEN)
                            ->enum(HousekeepingTaskStatus::class),
                        Forms\Components\Select::make('assigned_user_id')
                            ->relationship('assignedUser', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (HousekeepingTaskStatus $state) => $state->color())
                    ->formatStateUsing(fn ($state) => $state->label()),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(HousekeepingTaskStatus::class),
                Tables\Filters\SelectFilter::make('type')
                    ->options(HousekeepingTaskType::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHousekeepingTasks::route('/'),
            'create' => Pages\CreateHousekeepingTask::route('/create'),
            'edit' => Pages\EditHousekeepingTask::route('/{record}/edit'),
        ];
    }
}
