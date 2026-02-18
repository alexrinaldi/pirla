<?php

declare(strict_types=1);

namespace App\Filament\Resources\RatePlanResource\Pages;

use App\Filament\Resources\RatePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatePlan extends EditRecord
{
    protected static string $resource = RatePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
