<?php

declare(strict_types=1);

namespace App\Domain\Rate\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'date',
        'available_rooms',
        'min_stay',
        'max_stay',
        'closed',
        'closed_to_arrival',
        'closed_to_departure',
    ];

    protected $casts = [
        'date' => 'date',
        'available_rooms' => 'integer',
        'min_stay' => 'integer',
        'max_stay' => 'integer',
        'closed' => 'boolean',
        'closed_to_arrival' => 'boolean',
        'closed_to_departure' => 'boolean',
    ];

    /**
     * Get the room type for this availability.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\RoomType::class);
    }
}
