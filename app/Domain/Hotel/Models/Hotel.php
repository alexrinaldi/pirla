<?php

declare(strict_types=1);

namespace App\Domain\Hotel\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'tax_id',
        'currency',
        'timezone',
    ];

    /**
     * Get the users associated with this hotel.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the settings for this hotel.
     */
    public function hotelSettings(): HasOne
    {
        return $this->hasOne(HotelSettings::class);
    }

    /**
     * Get the room types for this hotel.
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(\App\Domain\Inventory\Models\RoomType::class);
    }

    /**
     * Get the rooms for this hotel.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(\App\Domain\Inventory\Models\Room::class);
    }

    /**
     * Get the amenities for this hotel.
     */
    public function amenities(): HasMany
    {
        return $this->hasMany(\App\Domain\Inventory\Models\Amenity::class);
    }

    /**
     * Get the reservations for this hotel.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(\App\Domain\Reservation\Models\Reservation::class);
    }

    /**
     * Get the guests for this hotel.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(\App\Domain\Guest\Models\Guest::class);
    }

    /**
     * Get the guest tags for this hotel.
     */
    public function guestTags(): HasMany
    {
        return $this->hasMany(\App\Domain\Guest\Models\GuestTag::class);
    }

    /**
     * Get the rate plans for this hotel.
     */
    public function ratePlans(): HasMany
    {
        return $this->hasMany(\App\Domain\Rate\Models\RatePlan::class);
    }

    /**
     * Get the availability records for this hotel.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(\App\Domain\Rate\Models\Availability::class);
    }

    /**
     * Get the invoices for this hotel.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Domain\Finance\Models\Invoice::class);
    }

    /**
     * Get the payments for this hotel.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(\App\Domain\Finance\Models\Payment::class);
    }

    /**
     * Get the housekeeping tasks for this hotel.
     */
    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\HousekeepingTask::class);
    }

    /**
     * Get the maintenance tickets for this hotel.
     */
    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\MaintenanceTicket::class);
    }
}
