<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Kantor Pusat Jakarta',
                'type' => 'kantor_pusat',
                'address' => 'Gedung Nikel Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan',
            ],
            [
                'name' => 'Kantor Cabang Makassar',
                'type' => 'kantor_cabang',
                'address' => 'Jl. AP Pettarani No. 45, Makassar, Sulawesi Selatan',
            ],
            [
                'name' => 'Tambang Pomalaa',
                'type' => 'tambang',
                'address' => 'Site Pomalaa, Kabupaten Kolaka, Sulawesi Tenggara',
            ],
            [
                'name' => 'Tambang Morowali',
                'type' => 'tambang',
                'address' => 'Kawasan Industri Bahodopi, Kabupaten Morowali, Sulawesi Tengah',
            ],
            [
                'name' => 'Tambang Weda Bay',
                'type' => 'tambang',
                'address' => 'Site Weda Bay, Kabupaten Halmahera Tengah, Maluku Utara',
            ],
            [
                'name' => 'Tambang Sorowako',
                'type' => 'tambang',
                'address' => 'Site Sorowako, Kabupaten Luwu Timur, Sulawesi Selatan',
            ],
            [
                'name' => 'Tambang Konawe',
                'type' => 'tambang',
                'address' => 'Site Morosi, Kabupaten Konawe, Sulawesi Tenggara',
            ],
            [
                'name' => 'Tambang Obi',
                'type' => 'tambang',
                'address' => 'Pulau Obi, Kabupaten Halmahera Selatan, Maluku Utara',
            ],
            [
                'name' => 'Kantor Cabang Kendari',
                'type' => 'kantor_cabang',
                'address' => 'Jl. Ahmad Yani No. 12, Kendari, Sulawesi Tenggara',
            ],
            [
                'name' => 'Kantor Cabang Palu',
                'type' => 'kantor_cabang',
                'address' => 'Jl. Diponegoro No. 27, Palu, Sulawesi Tengah',
            ],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        }
    }
}
