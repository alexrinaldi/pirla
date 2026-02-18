<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Guest\Models\Guest;
use App\Infrastructure\Support\Policies\ChecksHotelOwnership;
use App\Models\User;

class GuestPolicy
{
    use ChecksHotelOwnership;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Guest $guest): bool
    {
        return $this->userBelongsToHotel($user, $guest);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Guest $guest): bool
    {
        return $this->userBelongsToHotel($user, $guest);
    }

    public function delete(User $user, Guest $guest): bool
    {
        return $this->userBelongsToHotel($user, $guest);
    }

    public function restore(User $user, Guest $guest): bool
    {
        return $this->userBelongsToHotel($user, $guest);
    }

    public function forceDelete(User $user, Guest $guest): bool
    {
        return $this->userBelongsToHotel($user, $guest) 
            && $user->can('manage guests');
    }
}
