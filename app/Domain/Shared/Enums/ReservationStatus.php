<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum ReservationStatus: string
{
    case INQUIRY = 'inquiry';
    case OPTION = 'option';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case CHECKED_OUT = 'checked_out';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::INQUIRY => 'Inquiry',
            self::OPTION => 'Option',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked In',
            self::CHECKED_OUT => 'Checked Out',
            self::CANCELLED => 'Cancelled',
            self::NO_SHOW => 'No Show',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INQUIRY => 'gray',
            self::OPTION => 'info',
            self::CONFIRMED => 'success',
            self::CHECKED_IN => 'primary',
            self::CHECKED_OUT => 'warning',
            self::CANCELLED => 'danger',
            self::NO_SHOW => 'danger',
        };
    }
}
