<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum HousekeepingTaskStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Done',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'gray',
            self::IN_PROGRESS => 'warning',
            self::DONE => 'success',
        };
    }
}
