<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Policies;

use App\Domain\Hotel\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ChecksHotelOwnership
{
    protected function userBelongsToHotel(User $user, Model $model): bool
    {
        if (!property_exists($model, 'hotel_id') && !isset($model->hotel_id)) {
            return false;
        }

        return $user->hotels()->where('hotels.id', $model->hotel_id)->exists();
    }

    protected function userCanAccessHotel(User $user, Hotel $hotel): bool
    {
        return $user->hotels()->where('hotels.id', $hotel->id)->exists();
    }
}
