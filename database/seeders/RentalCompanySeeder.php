<?php

namespace Database\Seeders;

use App\Models\RentalCompany;
use Illuminate\Database\Seeder;

class RentalCompanySeeder extends Seeder
{
    public function run(): void
    {
        $rentals = [
            [
                'name' => 'PT Trans Tambang Nusantara',
                'contact_person' => 'Bambang Sukoco',
                'phone' => '082199887766',
                'address' => 'Jl. Perintis Kemerdekaan KM 10, Makassar',
            ],
            [
                'name' => 'PT Nikel Fleet Rent',
                'contact_person' => 'Erwin Prasetyo',
                'phone' => '081344556677',
                'address' => 'Jl. Yos Sudarso No. 88, Kendari',
            ],
            [
                'name' => 'PT Borneo Heavy Logistics',
                'contact_person' => 'Lukas Susanto',
                'phone' => '085211223344',
                'address' => 'Jl. Pelabuhan Samudera No. 15, Palu',
            ],
            [
                'name' => 'PT Sulawesi Mining Transport',
                'contact_person' => 'Andi Fajar',
                'phone' => '081355778899',
                'address' => 'Jl. Wolter Monginsidi No. 44, Kendari',
            ],
            [
                'name' => 'PT Halmahera Fleet Service',
                'contact_person' => 'Yunus Abdullah',
                'phone' => '082188334455',
                'address' => 'Jl. Raya Weda No. 9, Halmahera Tengah',
            ],
            [
                'name' => 'PT Obi Island Rentcar',
                'contact_person' => 'Fatimah Laode',
                'phone' => '085266778899',
                'address' => 'Dermaga Utama Pulau Obi, Halmahera Selatan',
            ],
        ];

        foreach ($rentals as $ren) {
            RentalCompany::create($ren);
        }
    }
}
