<?php

declare(strict_types=1);

namespace App\Domain\Rate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate_plan_id',
        'room_type_id',
        'date_from',
        'date_to',
        'days_of_week',
        'rate',
        'min_stay_override',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'days_of_week' => 'array',
        'rate' => 'decimal:2',
        'min_stay_override' => 'integer',
    ];

    /**
     * Get the rate plan that owns this rule.
     */
    public function ratePlan(): BelongsTo
    {
        return $this->belongsTo(RatePlan::class);
    }

    /**
     * Get the room type for this rule.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Inventory\Models\RoomType::class);
    }
}
