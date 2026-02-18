<?php

declare(strict_types=1);

namespace App\Domain\Reservation\Models;

use App\Domain\Shared\Enums\ReservationSource;
use App\Domain\Shared\Enums\ReservationStatus;
use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'confirmation_number',
        'status',
        'source',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'notes',
        'special_requests',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,
        'source' => ReservationSource::class,
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
    ];

    /**
     * Get the reservation items (room assignments) for this reservation.
     */
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    /**
     * Get the guests associated with this reservation.
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Domain\Guest\Models\Guest::class,
            'reservation_guest'
        )->withPivot('is_primary')->withTimestamps();
    }

    /**
     * Get the invoices for this reservation.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Domain\Finance\Models\Invoice::class);
    }

    /**
     * Get the primary guest for this reservation.
     */
    public function primaryGuest()
    {
        return $this->guests()->wherePivot('is_primary', true)->first();
    }

    /**
     * Calculate the number of nights.
     */
    public function getNightsAttribute(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }
}
