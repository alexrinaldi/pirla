<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case BANK_TRANSFER = 'bank_transfer';
    case OTA_VIRTUAL_CARD = 'ota_virtual_card';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::CARD => 'Card',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::OTA_VIRTUAL_CARD => 'OTA Virtual Card',
        };
    }
}
