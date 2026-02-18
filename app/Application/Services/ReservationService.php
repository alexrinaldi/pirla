<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Reservation\Models\Reservation;
use App\Domain\Reservation\Models\ReservationItem;
use App\Domain\Inventory\Models\Room;
use App\Domain\Inventory\Models\RoomType;
use App\Domain\Guest\Models\Guest;
use App\Domain\Shared\Enums\ReservationStatus;
use App\Domain\Shared\Enums\RoomStatus;
use App\Domain\Reservation\Events\ReservationCreated;
use App\Domain\Reservation\Events\ReservationCheckedIn;
use App\Domain\Reservation\Events\ReservationCheckedOut;
use App\Domain\Reservation\Events\ReservationCancelled;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReservationService
{
    public function __construct(
        protected AvailabilityService $availabilityService,
        protected PricingService $pricingService
    ) {
    }

    public function createReservation(array $data): Reservation
    {
        $data['code'] = $data['code'] ?? (string) Str::ulid();
        $data['status'] = $data['status'] ?? ReservationStatus::INQUIRY;

        $reservation = Reservation::create($data);

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $this->addReservationItem($reservation, $itemData);
            }
        }

        if (isset($data['guests']) && is_array($data['guests'])) {
            foreach ($data['guests'] as $guestId => $guestData) {
                $isPrimary = $guestData['is_primary'] ?? false;
                $reservation->guests()->attach($guestId, ['is_primary' => $isPrimary]);
            }
        }

        event(new ReservationCreated($reservation));

        return $reservation->fresh(['reservationItems', 'guests']);
    }

    public function addReservationItem(Reservation $reservation, array $itemData): ReservationItem
    {
        $roomType = RoomType::findOrFail($itemData['room_type_id']);
        $checkIn = Carbon::parse($itemData['date_from']);
        $checkOut = Carbon::parse($itemData['date_to']);

        $item = new ReservationItem($itemData);
        $item->reservation_id = $reservation->id;
        $item->save();

        return $item;
    }

    public function assignRoom(ReservationItem $reservationItem, Room $room): void
    {
        if ($reservationItem->room_type_id !== $room->room_type_id) {
            throw new \InvalidArgumentException('Room does not match reservation item room type');
        }

        $reservationItem->room_id = $room->id;
        $reservationItem->save();
    }

    public function checkIn(Reservation $reservation): void
    {
        if ($reservation->status !== ReservationStatus::CONFIRMED) {
            throw new \InvalidArgumentException('Only confirmed reservations can be checked in');
        }

        $reservation->status = ReservationStatus::CHECKED_IN;
        $reservation->save();

        foreach ($reservation->reservationItems as $item) {
            if ($item->room_id) {
                $room = Room::find($item->room_id);
                if ($room) {
                    $room->status = RoomStatus::OCCUPIED;
                    $room->save();
                }
            }
        }

        event(new ReservationCheckedIn($reservation));
    }

    public function checkOut(Reservation $reservation): void
    {
        if ($reservation->status !== ReservationStatus::CHECKED_IN) {
            throw new \InvalidArgumentException('Only checked-in reservations can be checked out');
        }

        $reservation->status = ReservationStatus::CHECKED_OUT;
        $reservation->save();

        foreach ($reservation->reservationItems as $item) {
            if ($item->room_id) {
                $room = Room::find($item->room_id);
                if ($room) {
                    $room->status = RoomStatus::AVAILABLE;
                    $room->save();
                }
            }
        }

        event(new ReservationCheckedOut($reservation));
    }

    public function cancel(Reservation $reservation): void
    {
        if (in_array($reservation->status, [ReservationStatus::CHECKED_OUT, ReservationStatus::CANCELLED])) {
            throw new \InvalidArgumentException('Cannot cancel a checked-out or already cancelled reservation');
        }

        $oldStatus = $reservation->status;
        $reservation->status = ReservationStatus::CANCELLED;
        $reservation->save();

        if ($oldStatus === ReservationStatus::CONFIRMED || $oldStatus === ReservationStatus::CHECKED_IN) {
            foreach ($reservation->reservationItems as $item) {
                if ($item->room_id) {
                    $room = Room::find($item->room_id);
                    if ($room && $room->status === RoomStatus::OCCUPIED) {
                        $room->status = RoomStatus::AVAILABLE;
                        $room->save();
                    }
                }
            }
        }

        event(new ReservationCancelled($reservation));
    }
}
