<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Angkutan Orang',
                'description' => 'Kendaraan penumpang operasional tambang, SUV 4x4, MPV, dan Minibus Crew',
            ],
            [
                'name' => 'Angkutan Barang',
                'description' => 'Kendaraan pengangkut hasil tambang, Dump Truck, Light Truck Cargo, dan Tanki BBM',
            ],
        ];

        foreach ($categories as $cat) {
            VehicleCategory::create($cat);
        }
    }
}
