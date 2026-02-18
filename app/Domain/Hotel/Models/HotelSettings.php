<?php

declare(strict_types=1);

namespace App\Domain\Hotel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'check_in_time',
        'check_out_time',
        'cancellation_hours',
        'default_rate_plan_id',
        'allow_overbooking',
        'send_confirmation_email',
        'send_reminder_email',
        'reminder_days_before',
        'tax_rate',
        'service_charge_rate',
    ];

    protected $casts = [
        'allow_overbooking' => 'boolean',
        'send_confirmation_email' => 'boolean',
        'send_reminder_email' => 'boolean',
        'reminder_days_before' => 'integer',
        'cancellation_hours' => 'integer',
        'tax_rate' => 'decimal:2',
        'service_charge_rate' => 'decimal:2',
    ];

    /**
     * Get the hotel that owns these settings.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
