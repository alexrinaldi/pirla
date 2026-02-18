<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\InvoiceLine;
use App\Domain\Guest\Models\Guest;
use App\Domain\Hotel\Models\Hotel;
use App\Domain\Inventory\Models\Room;
use App\Domain\Inventory\Models\RoomType;
use App\Domain\Reservation\Models\Reservation;
use App\Domain\Reservation\Models\ReservationItem;
use App\Domain\Shared\Enums\InvoiceStatus;
use App\Domain\Shared\Enums\ReservationSource;
use App\Domain\Shared\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Get the demo hotel
        $hotel = Hotel::where('name', 'Grand Plaza Hotel')->first();

        if (!$hotel) {
            $this->command->error('Demo hotel not found. Please run DemoHotelSeeder first.');
            return;
        }

        // Get Deluxe room type
        $deluxeRoomType = RoomType::where('hotel_id', $hotel->id)
            ->where('name', 'Deluxe Room')
            ->first();

        // Get a Deluxe room
        $deluxeRoom = Room::where('hotel_id', $hotel->id)
            ->where('room_type_id', $deluxeRoomType->id)
            ->where('room_number', '101')
            ->first();

        // Create demo guest
        $guest = Guest::create([
            'hotel_id' => $hotel->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'document_type' => 'Passport',
            'document_number' => 'AB1234567',
            'nationality' => 'United States',
            'address' => '456 Oak Avenue',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'country' => 'United States',
            'postal_code' => '90001',
            'notes' => 'Prefers non-smoking rooms',
        ]);

        // Create reservation for tomorrow, 2 nights
        $checkInDate = Carbon::tomorrow();
        $checkOutDate = $checkInDate->copy()->addDays(2);

        $reservation = Reservation::create([
            'hotel_id' => $hotel->id,
            'code' => Str::ulid()->toBase32(),
            'status' => ReservationStatus::CONFIRMED,
            'source' => ReservationSource::DIRECT,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'adults' => 2,
            'children' => 0,
            'notes' => 'Early check-in requested. High floor room if available.',
        ]);

        // Link guest to reservation as primary
        $reservation->guests()->attach($guest->id, ['is_primary' => true]);

        // Create reservation item
        $pricePerNight = 150.00;
        $taxPerNight = $pricePerNight * 0.10; // 10% tax
        $nights = 2;
        $total = ($pricePerNight + $taxPerNight) * $nights;

        ReservationItem::create([
            'reservation_id' => $reservation->id,
            'room_type_id' => $deluxeRoomType->id,
            'room_id' => $deluxeRoom->id,
            'date_from' => $checkInDate,
            'date_to' => $checkOutDate,
            'price_per_night' => $pricePerNight,
            'taxes_per_night' => $taxPerNight,
            'total' => $total,
        ]);

        // Calculate invoice amounts
        $subtotal = $pricePerNight * $nights;
        $taxes = $taxPerNight * $nights;
        $invoiceTotal = $subtotal + $taxes;

        // Create invoice
        $invoice = Invoice::create([
            'hotel_id' => $hotel->id,
            'reservation_id' => $reservation->id,
            'guest_id' => $guest->id,
            'number' => 'INV-' . str_pad((string) 1, 6, '0', STR_PAD_LEFT),
            'status' => InvoiceStatus::ISSUED,
            'issue_date' => now()->toDateString(),
            'due_date' => $checkOutDate->toDateString(),
            'subtotal' => $subtotal,
            'taxes' => $taxes,
            'total' => $invoiceTotal,
        ]);

        // Create invoice line items
        InvoiceLine::create([
            'invoice_id' => $invoice->id,
            'description' => "Deluxe Room - {$nights} night(s)",
            'quantity' => $nights,
            'unit_price' => $pricePerNight,
            'subtotal' => $subtotal,
            'tax_rate' => 10.00,
            'tax_amount' => $taxes,
            'total' => $invoiceTotal,
        ]);
    }
}
