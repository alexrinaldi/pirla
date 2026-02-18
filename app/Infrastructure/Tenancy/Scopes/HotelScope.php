<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenancy\Scopes;

use App\Infrastructure\Tenancy\CurrentHotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HotelScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->has(CurrentHotel::class)) {
            $currentHotel = app(CurrentHotel::class);
            
            if ($currentHotel->has()) {
                $builder->where($model->getTable() . '.hotel_id', $currentHotel->id());
            }
        }
    }
}
