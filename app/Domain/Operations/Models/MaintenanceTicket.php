<?php

declare(strict_types=1);

namespace App\Domain\Operations\Models;

use App\Domain\Shared\Enums\MaintenancePriority;
use App\Domain\Shared\Enums\MaintenanceStatus;
use App\Domain\Shared\Traits\BelongsToHotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTicket extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'reported_by',
        'reported_at',
        'started_at',
        'completed_at',
        'resolution_notes',
    ];

    protected $casts = [
        'priority' => MaintenancePriority::class,
        'status' => MaintenanceStatus::class,
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the room for this ticket.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\Room::class);
    }

    /**
     * Get the user assigned to this ticket.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who reported this ticket.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
