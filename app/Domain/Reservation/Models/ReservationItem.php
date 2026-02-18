<?php

declare(strict_types=1);

namespace App\Domain\Reservation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'room_type_id',
        'room_id',
        'rate',
        'adults',
        'children',
        'extra_person_charge',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'adults' => 'integer',
        'children' => 'integer',
        'extra_person_charge' => 'decimal:2',
    ];

    /**
     * Get the reservation that owns this item.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the room type for this item.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\RoomType::class);
    }

    /**
     * Get the assigned room for this item.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\Room::class);
    }
}
