<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum MaintenanceStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'gray',
            self::IN_PROGRESS => 'warning',
            self::RESOLVED => 'success',
        };
    }
}
