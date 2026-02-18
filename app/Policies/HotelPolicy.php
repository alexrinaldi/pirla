<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Hotel\Models\Hotel;
use App\Infrastructure\Support\Policies\ChecksHotelOwnership;
use App\Models\User;

class HotelPolicy
{
    use ChecksHotelOwnership;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hotel $hotel): bool
    {
        return $this->userCanAccessHotel($user, $hotel);
    }

    public function create(User $user): bool
    {
        return $user->can('manage hotels');
    }

    public function update(User $user, Hotel $hotel): bool
    {
        return $this->userCanAccessHotel($user, $hotel) 
            && $user->can('manage hotels');
    }

    public function delete(User $user, Hotel $hotel): bool
    {
        return $this->userCanAccessHotel($user, $hotel) 
            && $user->can('manage hotels');
    }
}
