<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Reservation\Models\Reservation;
use App\Infrastructure\Support\Policies\ChecksHotelOwnership;
use App\Models\User;

class ReservationPolicy
{
    use ChecksHotelOwnership;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $this->userBelongsToHotel($user, $reservation);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->userBelongsToHotel($user, $reservation);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->userBelongsToHotel($user, $reservation) 
            && $user->can('manage reservations');
    }
}
