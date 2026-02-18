<?php

declare(strict_types=1);

namespace App\Domain\Reservation\Events;

use App\Domain\Reservation\Models\Reservation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelled
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Reservation $reservation
    ) {
    }
}
