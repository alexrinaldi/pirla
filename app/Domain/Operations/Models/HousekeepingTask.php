<?php

declare(strict_types=1);

namespace App\Domain\Operations\Models;

use App\Domain\Shared\Enums\HousekeepingTaskStatus;
use App\Domain\Shared\Enums\HousekeepingTaskType;
use App\Domain\Shared\Traits\BelongsToHotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousekeepingTask extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_id',
        'assigned_to',
        'type',
        'status',
        'priority',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'type' => HousekeepingTaskType::class,
        'status' => HousekeepingTaskStatus::class,
        'priority' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the room for this task.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\Room::class);
    }

    /**
     * Get the user assigned to this task.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
