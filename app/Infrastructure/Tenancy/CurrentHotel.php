<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenancy;

use App\Domain\Hotel\Models\Hotel;
use Illuminate\Support\Facades\Session;

class CurrentHotel
{
    protected ?Hotel $hotel = null;

    public function set(Hotel $hotel): void
    {
        $this->hotel = $hotel;
        Session::put('current_hotel_id', $hotel->id);
    }

    public function get(): ?Hotel
    {
        if ($this->hotel) {
            return $this->hotel;
        }

        $hotelId = Session::get('current_hotel_id');
        
        if ($hotelId) {
            $this->hotel = Hotel::find($hotelId);
        }

        return $this->hotel;
    }

    public function id(): ?int
    {
        return $this->get()?->id;
    }

    public function has(): bool
    {
        return $this->get() !== null;
    }

    public function clear(): void
    {
        $this->hotel = null;
        Session::forget('current_hotel_id');
    }
}
