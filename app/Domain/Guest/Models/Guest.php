<?php

declare(strict_types=1);

namespace App\Domain\Guest\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use BelongsToHotel, HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'id_type',
        'id_number',
        'date_of_birth',
        'address',
        'city',
        'state',
        'postal_code',
        'preferences',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'preferences' => 'array',
    ];

    /**
     * Get the reservations for this guest.
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Domain\Reservation\Models\Reservation::class,
            'reservation_guest'
        )->withPivot('is_primary');
    }

    /**
     * Get the tags for this guest.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(GuestTag::class, 'guest_guest_tag');
    }

    /**
     * Get the invoices for this guest.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Domain\Finance\Models\Invoice::class);
    }

    /**
     * Get the guest's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
