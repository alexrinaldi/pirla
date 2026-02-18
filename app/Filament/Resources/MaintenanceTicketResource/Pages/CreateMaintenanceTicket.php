<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaintenanceTicketResource\Pages;

use App\Filament\Resources\MaintenanceTicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceTicket extends CreateRecord
{
    protected static string $resource = MaintenanceTicketResource::class;
}
