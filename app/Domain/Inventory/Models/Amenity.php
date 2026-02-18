<?php

declare(strict_types=1);

namespace App\Domain\Inventory\Models;

use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'icon',
        'description',
    ];

    /**
     * Get the room types that have this amenity.
     */
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'room_type_amenity')
            ->withTimestamps();
    }
}
