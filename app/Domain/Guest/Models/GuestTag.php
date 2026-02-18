<?php

declare(strict_types=1);

namespace App\Domain\Guest\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GuestTag extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'color',
        'description',
    ];

    /**
     * Get the guests that have this tag.
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'guest_guest_tag')
            ->withTimestamps();
    }
}
