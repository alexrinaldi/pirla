<?php

declare(strict_types=1);

namespace App\Domain\Shared\Traits;

use App\Infrastructure\Tenancy\Scopes\HotelScope;
use App\Infrastructure\Tenancy\CurrentHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToHotel
{
    protected static function bootBelongsToHotel(): void
    {
        static::addGlobalScope(new HotelScope());

        static::creating(function (Model $model) {
            if (!$model->hotel_id && app()->has(CurrentHotel::class)) {
                $model->hotel_id = app(CurrentHotel::class)->id();
            }
        });
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Hotel\Models\Hotel::class);
    }
}
