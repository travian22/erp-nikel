<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
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

        $drivers = [
            [
                'name' => 'Syarifuddin',
                'license_number' => 'SIM-B2-99887766',
                'license_expiry' => '2028-12-31',
                'phone' => '081299001122',
                'location_id' => $headOffice->id,
                'status' => 'available',
            ],
            [
                'name' => 'Junaedi Mappasomba',
                'license_number' => 'SIM-B2-88776655',
                'license_expiry' => '2027-10-15',
                'phone' => '081299001123',
                'location_id' => $pomalaa->id,
                'status' => 'available',
            ],
            [
                'name' => 'Usman Harun',
                'license_number' => 'SIM-B2-77665544',
                'license_expiry' => '2029-05-20',
                'phone' => '081299001124',
                'location_id' => $morowali->id,
                'status' => 'on_duty',
            ],
            [
                'name' => 'Kurniawan Dwi',
                'license_number' => 'SIM-A-66554433',
                'license_expiry' => '2027-08-10',
                'phone' => '081299001125',
                'location_id' => $pomalaa->id,
                'status' => 'off',
            ],
            [
                'name' => 'Andi Rahman',
                'license_number' => 'SIM-A-55443322',
                'license_expiry' => '2028-03-12',
                'phone' => '081299001126',
                'location_id' => $headOffice->id,
                'status' => 'on_duty',
            ],
            [
                'name' => 'Hasan Basri',
                'license_number' => 'SIM-B1-44332211',
                'license_expiry' => '2027-11-05',
                'phone' => '081299001127',
                'location_id' => $makassar->id,
                'status' => 'available',
            ],
            [
                'name' => 'La Ode Ridwan',
                'license_number' => 'SIM-B2-33221100',
                'license_expiry' => '2029-01-22',
                'phone' => '081299001128',
                'location_id' => $kendari->id,
                'status' => 'available',
            ],
            [
                'name' => 'Irfan Mahmud',
                'license_number' => 'SIM-B1-22110099',
                'license_expiry' => '2026-09-18',
                'phone' => '081299001129',
                'location_id' => $palu->id,
                'status' => 'available',
            ],
            [
                'name' => 'Supriyadi',
                'license_number' => 'SIM-B2-11009988',
                'license_expiry' => '2028-07-30',
                'phone' => '081299001130',
                'location_id' => $pomalaa->id,
                'status' => 'on_duty',
            ],
            [
                'name' => 'Wahyu Ningsih',
                'license_number' => 'SIM-A-00998877',
                'license_expiry' => '2027-04-14',
                'phone' => '081299001131',
                'location_id' => $pomalaa->id,
                'status' => 'available',
            ],
            [
                'name' => 'Abdullah Saleh',
                'license_number' => 'SIM-B2-99880011',
                'license_expiry' => '2029-08-08',
                'phone' => '081299001132',
                'location_id' => $morowali->id,
                'status' => 'available',
            ],
            [
                'name' => 'Rizal Mahendra',
                'license_number' => 'SIM-B1-88771122',
                'license_expiry' => '2026-12-01',
                'phone' => '081299001133',
                'location_id' => $morowali->id,
                'status' => 'off',
            ],
            [
                'name' => 'Yusuf Maluku',
                'license_number' => 'SIM-B2-77662233',
                'license_expiry' => '2028-02-19',
                'phone' => '081299001134',
                'location_id' => $wedaBay->id,
                'status' => 'on_duty',
            ],
            [
                'name' => 'Hendra Gunawan',
                'license_number' => 'SIM-A-66553344',
                'license_expiry' => '2027-06-25',
                'phone' => '081299001135',
                'location_id' => $wedaBay->id,
                'status' => 'available',
            ],
            [
                'name' => 'Paulus Rante',
                'license_number' => 'SIM-B1-55444455',
                'license_expiry' => '2029-09-09',
                'phone' => '081299001136',
                'location_id' => $sorowako->id,
                'status' => 'available',
            ],
            [
                'name' => 'Arman Jaya',
                'license_number' => 'SIM-B2-44335566',
                'license_expiry' => '2028-05-17',
                'phone' => '081299001137',
                'location_id' => $konawe->id,
                'status' => 'available',
            ],
            [
                'name' => 'Salim Daeng',
                'license_number' => 'SIM-A-33226677',
                'license_expiry' => '2027-01-03',
                'phone' => '081299001138',
                'location_id' => $obi->id,
                'status' => 'available',
            ],
        ];

        foreach ($drivers as $d) {
            Driver::create($d);
        }
    }
}
