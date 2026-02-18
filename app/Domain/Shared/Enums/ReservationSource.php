<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum ReservationSource: string
{
    case DIRECT = 'direct';
    case OTA = 'ota';
    case PHONE = 'phone';
    case EMAIL = 'email';

    public function label(): string
    {
        return match ($this) {
            self::DIRECT => 'Direct',
            self::OTA => 'OTA',
            self::PHONE => 'Phone',
            self::EMAIL => 'Email',
        };
    }
}
