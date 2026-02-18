<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Rate\Models\Availability;
use App\Domain\Inventory\Models\RoomType;
use Carbon\Carbon;

class AvailabilityService
{
    public function checkRoomTypeAvailability(
        RoomType $roomType,
        Carbon $checkIn,
        Carbon $checkOut,
        int $requiredRooms = 1
    ): bool {
        $dates = [];
        $current = $checkIn->copy();
        
        while ($current->lt($checkOut)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        foreach ($dates as $date) {
            $availability = Availability::where('room_type_id', $roomType->id)
                ->where('date', $date)
                ->first();

            if ($availability && $availability->stop_sell) {
                return false;
            }

            if ($availability && $availability->allotment < $requiredRooms) {
                return false;
            }

            $totalRooms = $roomType->rooms()->where('is_active', true)->count();
            $availableRooms = $availability?->allotment ?? $totalRooms;

            if ($availableRooms < $requiredRooms) {
                return false;
            }
        }

        return true;
    }

    public function reserveInventory(
        RoomType $roomType,
        Carbon $checkIn,
        Carbon $checkOut,
        int $roomsToReserve = 1
    ): void {
        $dates = [];
        $current = $checkIn->copy();
        
        while ($current->lt($checkOut)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        foreach ($dates as $date) {
            $availability = Availability::firstOrCreate(
                [
                    'room_type_id' => $roomType->id,
                    'date' => $date,
                ],
                [
                    'allotment' => $roomType->rooms()->where('is_active', true)->count(),
                    'stop_sell' => false,
                ]
            );

            $availability->decrement('allotment', $roomsToReserve);
        }
    }

    public function releaseInventory(
        RoomType $roomType,
        Carbon $checkIn,
        Carbon $checkOut,
        int $roomsToRelease = 1
    ): void {
        $dates = [];
        $current = $checkIn->copy();
        
        while ($current->lt($checkOut)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        foreach ($dates as $date) {
            $availability = Availability::where('room_type_id', $roomType->id)
                ->where('date', $date)
                ->first();

            if ($availability) {
                $availability->increment('allotment', $roomsToRelease);
            }
        }
    }
}
