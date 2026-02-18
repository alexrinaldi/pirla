<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Hotel\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the demo hotel
        $hotel = Hotel::where('name', 'Grand Plaza Hotel')->first();

        if (!$hotel) {
            $this->command->error('Demo hotel not found. Please run DemoHotelSeeder first.');
            return;
        }

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->hotels()->attach($hotel->id);
        $admin->assignRole('admin');

        // Create Manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $manager->hotels()->attach($hotel->id);
        $manager->assignRole('manager');

        // Create Receptionist user
        $receptionist = User::create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $receptionist->hotels()->attach($hotel->id);
        $receptionist->assignRole('receptionist');
    }
}
