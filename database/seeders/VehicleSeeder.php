<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\RentalCompany;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $personCat = VehicleCategory::where('name', 'Angkutan Orang')->first();
        $goodsCat = VehicleCategory::where('name', 'Angkutan Barang')->first();

        $rental1 = RentalCompany::where('name', 'PT Trans Tambang Nusantara')->first();
        $rental2 = RentalCompany::where('name', 'PT Nikel Fleet Rent')->first();
        $rental3 = RentalCompany::where('name', 'PT Borneo Heavy Logistics')->first();
        $rental4 = RentalCompany::where('name', 'PT Sulawesi Mining Transport')->first();
        $rental5 = RentalCompany::where('name', 'PT Halmahera Fleet Service')->first();
        $rental6 = RentalCompany::where('name', 'PT Obi Island Rentcar')->first();

        $headOffice = Location::where('name', 'Kantor Pusat Jakarta')->first();
        $makassar = Location::where('name', 'Kantor Cabang Makassar')->first();
        $kendari = Location::where('name', 'Kantor Cabang Kendari')->first();
        $palu = Location::where('name', 'Kantor Cabang Palu')->first();
        $pomalaa = Location::where('name', 'Tambang Pomalaa')->first();
        $morowali = Location::where('name', 'Tambang Morowali')->first();
        $wedaBay = Location::where('name', 'Tambang Weda Bay')->first();
        $sorowako = Location::where('name', 'Tambang Sorowako')->first();
        $konawe = Location::where('name', 'Tambang Konawe')->first();
        $obi = Location::where('name', 'Tambang Obi')->first();

        $vehicles = [
            [
                'plate_number' => 'B 1001 NKL',
                'brand' => 'Toyota',
                'model' => 'Fortuner 2.8 4x4',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $headOffice->id,
                'status' => 'tersedia',
                'capacity' => 7,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DT 7002 PK',
                'brand' => 'Toyota',
                'model' => 'Hilux Double Cabin 4x4',
                'year' => 2024,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $pomalaa->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DN 8003 MR',
                'brand' => 'Mitsubishi',
                'model' => 'Triton 4x4 Ultimate',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental1->id,
                'location_id' => $morowali->id,
                'status' => 'digunakan',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DG 9004 WB',
                'brand' => 'Isuzu',
                'model' => 'Giga FVZ 285 Dump Truck',
                'year' => 2022,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental2->id,
                'location_id' => $wedaBay->id,
                'status' => 'tersedia',
                'capacity' => 20000, // Kg/liter
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DT 7005 Tanker',
                'brand' => 'Hino',
                'model' => 'Ranger FM 260 Fuel Tanker',
                'year' => 2021,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $pomalaa->id,
                'status' => 'maintenance',
                'capacity' => 16000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DN 8006 Bus',
                'brand' => 'Mitsubishi Fuso',
                'model' => 'Canter Coaster Minibus 24-Seat',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental1->id,
                'location_id' => $morowali->id,
                'status' => 'tersedia',
                'capacity' => 24,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'B 1102 NKL',
                'brand' => 'Toyota',
                'model' => 'Innova Zenix Hybrid',
                'year' => 2024,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $headOffice->id,
                'status' => 'tersedia',
                'capacity' => 7,
                'fuel_type' => 'Pertamax',
            ],
            [
                'plate_number' => 'B 1103 NKL',
                'brand' => 'Mitsubishi',
                'model' => 'Pajero Sport Dakar',
                'year' => 2022,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $headOffice->id,
                'status' => 'digunakan',
                'capacity' => 7,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DD 1201 MK',
                'brand' => 'Toyota',
                'model' => 'Hilux Double Cabin',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $makassar->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DT 1202 KD',
                'brand' => 'Isuzu',
                'model' => 'Elf NLR 55',
                'year' => 2021,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental4->id,
                'location_id' => $kendari->id,
                'status' => 'tersedia',
                'capacity' => 4000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DN 1303 PL',
                'brand' => 'Mitsubishi Fuso',
                'model' => 'Canter FE 74',
                'year' => 2022,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental3->id,
                'location_id' => $palu->id,
                'status' => 'tersedia',
                'capacity' => 5000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DT 7110 PK',
                'brand' => 'Toyota',
                'model' => 'Land Cruiser 70',
                'year' => 2024,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $pomalaa->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DT 7111 PK',
                'brand' => 'Toyota',
                'model' => 'Avanza 1.5 G',
                'year' => 2020,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $pomalaa->id,
                'status' => 'maintenance',
                'capacity' => 7,
                'fuel_type' => 'Pertalite',
            ],
            [
                'plate_number' => 'DT 7112 PK',
                'brand' => 'Hino',
                'model' => 'Ranger FM 260 Dump',
                'year' => 2021,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental1->id,
                'location_id' => $pomalaa->id,
                'status' => 'digunakan',
                'capacity' => 20000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DN 8110 MR',
                'brand' => 'Mitsubishi',
                'model' => 'Pajero Sport Exceed',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $morowali->id,
                'status' => 'tersedia',
                'capacity' => 7,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DN 8111 MR',
                'brand' => 'Isuzu',
                'model' => 'Elf NKR 71',
                'year' => 2022,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental1->id,
                'location_id' => $morowali->id,
                'status' => 'tersedia',
                'capacity' => 6000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DN 8112 MR',
                'brand' => 'Hino',
                'model' => 'Dutro 130 HD',
                'year' => 2020,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $morowali->id,
                'status' => 'maintenance',
                'capacity' => 8000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DG 9110 WB',
                'brand' => 'Toyota',
                'model' => 'Hilux Double Cabin',
                'year' => 2024,
                'category_id' => $personCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental5->id,
                'location_id' => $wedaBay->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DG 9111 WB',
                'brand' => 'Isuzu',
                'model' => 'Giga FVZ Dump Truck',
                'year' => 2023,
                'category_id' => $goodsCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental5->id,
                'location_id' => $wedaBay->id,
                'status' => 'digunakan',
                'capacity' => 20000,
                'fuel_type' => 'Solar',
            ],
            [
                'plate_number' => 'DS 6110 SR',
                'brand' => 'Toyota',
                'model' => 'Fortuner 2.8 VRZ',
                'year' => 2022,
                'category_id' => $personCat->id,
                'ownership_type' => 'milik_sendiri',
                'rental_company_id' => null,
                'location_id' => $sorowako->id,
                'status' => 'tersedia',
                'capacity' => 7,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DT 5110 KN',
                'brand' => 'Mitsubishi',
                'model' => 'Triton 4x4 HDX',
                'year' => 2023,
                'category_id' => $personCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental4->id,
                'location_id' => $konawe->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
            [
                'plate_number' => 'DG 4110 OB',
                'brand' => 'Toyota',
                'model' => 'Hilux Double Cabin',
                'year' => 2021,
                'category_id' => $personCat->id,
                'ownership_type' => 'sewa',
                'rental_company_id' => $rental6->id,
                'location_id' => $obi->id,
                'status' => 'tersedia',
                'capacity' => 5,
                'fuel_type' => 'Dexlite',
            ],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create($v);
        }
    }
}
