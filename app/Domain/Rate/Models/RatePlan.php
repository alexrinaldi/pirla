<?php

declare(strict_types=1);

namespace App\Domain\Rate\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatePlan extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'code',
        'description',
        'is_active',
        'min_stay',
        'max_stay',
        'advance_booking_days',
        'cancellation_hours',
        'is_refundable',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_stay' => 'integer',
        'max_stay' => 'integer',
        'advance_booking_days' => 'integer',
        'cancellation_hours' => 'integer',
        'is_refundable' => 'boolean',
    ];

    /**
     * Get the rate rules for this rate plan.
     */
    public function rateRules(): HasMany
    {
        return $this->hasMany(RateRule::class);
    }
}
