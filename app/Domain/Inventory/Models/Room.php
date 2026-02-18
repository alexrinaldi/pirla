<?php

declare(strict_types=1);

namespace App\Domain\Inventory\Models;

use App\Domain\Shared\Enums\RoomStatus;
use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'room_number',
        'floor',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => RoomStatus::class,
        'floor' => 'integer',
    ];

    /**
     * Get the room type for this room.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the reservation items for this room.
     */
    public function reservationItems(): HasMany
    {
        return $this->hasMany(\App\Domain\Reservation\Models\ReservationItem::class);
    }

    /**
     * Get the housekeeping tasks for this room.
     */
    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\HousekeepingTask::class);
    }

    /**
     * Get the maintenance tickets for this room.
     */
    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\MaintenanceTicket::class);
    }
}
