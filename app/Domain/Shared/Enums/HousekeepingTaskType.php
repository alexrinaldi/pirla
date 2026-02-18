<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum HousekeepingTaskType: string
{
    case CLEAN = 'clean';
    case INSPECTION = 'inspection';
    case TURN_DOWN = 'turn_down';

    public function label(): string
    {
        return match ($this) {
            self::CLEAN => 'Clean',
            self::INSPECTION => 'Inspection',
            self::TURN_DOWN => 'Turn Down',
        };
    }
}
