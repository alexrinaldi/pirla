<?php

declare(strict_types=1);

namespace App\Filament\Resources\HousekeepingTaskResource\Pages;

use App\Filament\Resources\HousekeepingTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHousekeepingTask extends CreateRecord
{
    protected static string $resource = HousekeepingTaskResource::class;
}
