<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Inventory\Models\Room;
use App\Domain\Reservation\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;

class ReservationPlanning extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Front Desk';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.reservation-planning';

    protected static ?string $title = 'Reservation Planning';

    public function getRooms()
    {
        return Room::with('roomType')->orderBy('room_number')->get();
    }

    public function getDateRange(): array
    {
        $start = Carbon::today();
        $end = Carbon::today()->addDays(13);
        
        return CarbonPeriod::create($start, $end)->toArray();
    }

    public function getReservations(): array
    {
        $start = Carbon::today();
        $end = Carbon::today()->addDays(13);

        return Reservation::with(['reservationItems.room', 'guests'])
            ->where('check_in_date', '<=', $end)
            ->where('check_out_date', '>=', $start)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->get()
            ->groupBy(function ($reservation) {
                return $reservation->reservationItems->pluck('room_id')->filter()->join(',');
            })
            ->toArray();
    }

    public function getReservationForRoomAndDate(int $roomId, Carbon $date)
    {
        return Reservation::with(['reservationItems', 'guests'])
            ->whereHas('reservationItems', function ($query) use ($roomId, $date) {
                $query->where('room_id', $roomId)
                    ->where('date_from', '<=', $date)
                    ->where('date_to', '>', $date);
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->first();
    }
}
