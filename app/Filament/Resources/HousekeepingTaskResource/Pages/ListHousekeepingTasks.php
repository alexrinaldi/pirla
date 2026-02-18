<?php

declare(strict_types=1);

namespace App\Filament\Resources\HousekeepingTaskResource\Pages;

use App\Filament\Resources\HousekeepingTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHousekeepingTasks extends ListRecords
{
    protected static string $resource = HousekeepingTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
