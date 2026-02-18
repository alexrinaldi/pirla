<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Hotel\Models\Hotel;
use App\Domain\Hotel\Models\HotelSettings;
use App\Domain\Inventory\Models\Amenity;
use App\Domain\Inventory\Models\Room;
use App\Domain\Inventory\Models\RoomType;
use App\Domain\Rate\Models\Availability;
use App\Domain\Rate\Models\RatePlan;
use App\Domain\Shared\Enums\RoomStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoHotelSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo hotel
        $hotel = Hotel::create([
            'name' => 'Grand Plaza Hotel',
            'slug' => 'grand-plaza-hotel',
            'address' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'United States',
            'postal_code' => '10001',
            'phone' => '+1 (212) 555-0100',
            'email' => 'info@grandplaza.com',
            'is_active' => true,
        ]);

        // Create hotel settings
        HotelSettings::create([
            'hotel_id' => $hotel->id,
            'currency' => 'USD',
            'timezone' => 'America/New_York',
            'tax_percentage' => 10.00,
            'checkin_time' => '15:00',
            'checkout_time' => '11:00',
        ]);

        // Create room types
        $deluxeRoomType = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Deluxe Room',
            'description' => 'Spacious and comfortable room with modern amenities',
            'capacity' => 2,
            'beds' => 1,
            'base_occupancy' => 2,
            'max_occupancy' => 2,
            'is_active' => true,
        ]);

        $suiteRoomType = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Suite',
            'description' => 'Luxurious suite with separate living area and premium amenities',
            'capacity' => 4,
            'beds' => 2,
            'base_occupancy' => 2,
            'max_occupancy' => 4,
            'is_active' => true,
        ]);

        // Create amenities
        $wifi = Amenity::create([
            'hotel_id' => $hotel->id,
            'name' => 'WiFi',
            'icon' => 'heroicon-o-wifi',
        ]);

        $tv = Amenity::create([
            'hotel_id' => $hotel->id,
            'name' => 'TV',
            'icon' => 'heroicon-o-tv',
        ]);

        $miniBar = Amenity::create([
            'hotel_id' => $hotel->id,
            'name' => 'Mini Bar',
            'icon' => 'heroicon-o-cube',
        ]);

        // Associate amenities with room types
        $deluxeRoomType->amenities()->attach([$wifi->id, $tv->id]);
        $suiteRoomType->amenities()->attach([$wifi->id, $tv->id, $miniBar->id]);

        // Create Deluxe rooms (101-107)
        $deluxeRoomNumbers = ['101', '102', '103', '104', '105', '106', '107'];
        foreach ($deluxeRoomNumbers as $roomNumber) {
            Room::create([
                'hotel_id' => $hotel->id,
                'room_type_id' => $deluxeRoomType->id,
                'room_number' => $roomNumber,
                'floor' => 1,
                'status' => RoomStatus::AVAILABLE,
            ]);
        }

        // Create Suite rooms (201-203)
        $suiteRoomNumbers = ['201', '202', '203'];
        foreach ($suiteRoomNumbers as $roomNumber) {
            Room::create([
                'hotel_id' => $hotel->id,
                'room_type_id' => $suiteRoomType->id,
                'room_number' => $roomNumber,
                'floor' => 2,
                'status' => RoomStatus::AVAILABLE,
            ]);
        }

        // Create rate plan
        $ratePlan = RatePlan::create([
            'hotel_id' => $hotel->id,
            'name' => 'Standard Rate',
            'description' => 'Standard flexible rate with free cancellation',
            'is_active' => true,
        ]);

        // Create availability records for next 30 days
        $startDate = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);

            // Deluxe Room availability
            Availability::create([
                'hotel_id' => $hotel->id,
                'room_type_id' => $deluxeRoomType->id,
                'date' => $date,
                'allotment' => 7,
                'stop_sell' => false,
            ]);

            // Suite availability
            Availability::create([
                'hotel_id' => $hotel->id,
                'room_type_id' => $suiteRoomType->id,
                'date' => $date,
                'allotment' => 3,
                'stop_sell' => false,
            ]);
        }
    }
}
