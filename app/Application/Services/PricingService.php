<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Rate\Models\RatePlan;
use App\Domain\Rate\Models\RateRule;
use App\Domain\Inventory\Models\RoomType;
use App\Domain\Reservation\Models\ReservationItem;
use Carbon\Carbon;

class PricingService
{
    public function calculateReservationItemTotal(
        ReservationItem $reservationItem,
        ?RatePlan $ratePlan = null
    ): array {
        $checkIn = Carbon::parse($reservationItem->date_from);
        $checkOut = Carbon::parse($reservationItem->date_to);
        $nights = $checkIn->diffInDays($checkOut);

        $pricePerNight = $reservationItem->price_per_night;
        $taxesPerNight = $reservationItem->taxes_per_night;

        if ($ratePlan) {
            $calculatedPrice = $this->applyRateRules(
                $ratePlan,
                $reservationItem->roomType,
                $checkIn,
                $checkOut
            );
            
            if ($calculatedPrice > 0) {
                $pricePerNight = $calculatedPrice;
            }
        }

        $subtotal = $pricePerNight * $nights;
        $taxTotal = $taxesPerNight * $nights;
        $total = $subtotal + $taxTotal;

        return [
            'price_per_night' => $pricePerNight,
            'taxes_per_night' => $taxesPerNight,
            'nights' => $nights,
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $total,
        ];
    }

    public function applyRateRules(
        RatePlan $ratePlan,
        RoomType $roomType,
        Carbon $checkIn,
        Carbon $checkOut
    ): float {
        $rules = RateRule::where('rate_plan_id', $ratePlan->id)
            ->where(function ($query) use ($roomType) {
                $query->where('room_type_id', $roomType->id)
                    ->orWhereNull('room_type_id');
            })
            ->where('date_from', '<=', $checkIn)
            ->where('date_to', '>=', $checkOut)
            ->orderByDesc('price_override')
            ->first();

        if ($rules && $rules->price_override) {
            return (float) $rules->price_override;
        }

        return 0.0;
    }
}
