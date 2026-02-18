<?php

declare(strict_types=1);

namespace App\Domain\Inventory\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'code',
        'description',
        'max_occupancy',
        'base_price',
        'extra_person_charge',
        'size_sqm',
        'sort_order',
    ];

    protected $casts = [
        'max_occupancy' => 'integer',
        'base_price' => 'decimal:2',
        'extra_person_charge' => 'decimal:2',
        'size_sqm' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Get the rooms of this type.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the amenities for this room type.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_type_amenity');
    }

    /**
     * Get the reservation items for this room type.
     */
    public function reservationItems(): HasMany
    {
        return $this->hasMany(\App\Domain\Reservation\Models\ReservationItem::class);
    }

    /**
     * Get the rate rules for this room type.
     */
    public function rateRules(): HasMany
    {
        return $this->hasMany(\App\Domain\Rate\Models\RateRule::class);
    }

    /**
     * Get the availability records for this room type.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(\App\Domain\Rate\Models\Availability::class);
    }
}
