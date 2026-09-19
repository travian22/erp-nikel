<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            VehicleCategorySeeder::class,
            RentalCompanySeeder::class,
            VehicleSeeder::class,
            DriverSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
