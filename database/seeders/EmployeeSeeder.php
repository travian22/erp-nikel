<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Location;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $headOffice = Location::where('type', 'kantor_pusat')->first();
        $makassar = Location::where('name', 'Kantor Cabang Makassar')->first();
        $kendari = Location::where('name', 'Kantor Cabang Kendari')->first();
        $palu = Location::where('name', 'Kantor Cabang Palu')->first();
        $pomalaa = Location::where('name', 'Tambang Pomalaa')->first();
        $morowali = Location::where('name', 'Tambang Morowali')->first();
        $wedaBay = Location::where('name', 'Tambang Weda Bay')->first();
        $sorowako = Location::where('name', 'Tambang Sorowako')->first();
        $konawe = Location::where('name', 'Tambang Konawe')->first();
        $obi = Location::where('name', 'Tambang Obi')->first();

        $employees = [
            [
                'nip' => 'EMP-001',
                'name' => 'Budi Santoso',
                'position' => 'Admin Logistik',
                'department' => 'Logistik & Operasional',
                'location_id' => $headOffice->id,
                'phone' => '081234567801',
                'email' => 'admin@nikel.co.id',
            ],
            [
                'nip' => 'EMP-002',
                'name' => 'Ahmad Dahlan',
                'position' => 'Manager Logistik (Approver L1)',
                'department' => 'Logistik & Operasional',
                'location_id' => $headOffice->id,
                'phone' => '081234567802',
                'email' => 'approver1@nikel.co.id',
            ],
            [
                'nip' => 'EMP-003',
                'name' => 'Siti Rahmawati',
                'position' => 'General Manager Ops (Approver L2)',
                'department' => 'Direksi Operasional',
                'location_id' => $headOffice->id,
                'phone' => '081234567803',
                'email' => 'approver2@nikel.co.id',
            ],
            [
                'nip' => 'EMP-004',
                'name' => 'Hendra Wijaya',
                'position' => 'Field Engineer',
                'department' => 'Eksplorasi Tambang',
                'location_id' => $pomalaa->id,
                'phone' => '081234567804',
                'email' => 'hendra.w@nikel.co.id',
            ],
            [
                'nip' => 'EMP-005',
                'name' => 'Rahmat Hidayat',
                'position' => 'Safety Officer',
                'department' => 'HSE & Safety',
                'location_id' => $morowali->id,
                'phone' => '081234567805',
                'email' => 'rahmat.h@nikel.co.id',
            ],
            [
                'nip' => 'EMP-006',
                'name' => 'Nurul Aisyah',
                'position' => 'Staff Administrasi',
                'department' => 'Logistik & Operasional',
                'location_id' => $makassar->id,
                'phone' => '081234567806',
                'email' => 'nurul.a@nikel.co.id',
            ],
            [
                'nip' => 'EMP-007',
                'name' => 'Fajar Ramadhan',
                'position' => 'Supervisor Site',
                'department' => 'Operasi Tambang',
                'location_id' => $pomalaa->id,
                'phone' => '081234567807',
                'email' => 'fajar.r@nikel.co.id',
            ],
            [
                'nip' => 'EMP-008',
                'name' => 'Dewi Lestari',
                'position' => 'Geologist',
                'department' => 'Eksplorasi Tambang',
                'location_id' => $wedaBay->id,
                'phone' => '081234567808',
                'email' => 'dewi.l@nikel.co.id',
            ],
            [
                'nip' => 'EMP-009',
                'name' => 'Agus Salim',
                'position' => 'Foreman Produksi',
                'department' => 'Operasi Tambang',
                'location_id' => $morowali->id,
                'phone' => '081234567809',
                'email' => 'agus.s@nikel.co.id',
            ],
            [
                'nip' => 'EMP-010',
                'name' => 'Putri Maharani',
                'position' => 'Staff HSE',
                'department' => 'HSE & Safety',
                'location_id' => $sorowako->id,
                'phone' => '081234567810',
                'email' => 'putri.m@nikel.co.id',
            ],
            [
                'nip' => 'EMP-011',
                'name' => 'Rudi Hartono',
                'position' => 'Mekanik Senior',
                'department' => 'Pemeliharaan Armada',
                'location_id' => $konawe->id,
                'phone' => '081234567811',
                'email' => 'rudi.h@nikel.co.id',
            ],
            [
                'nip' => 'EMP-012',
                'name' => 'Lina Marlina',
                'position' => 'Koordinator Logistik',
                'department' => 'Logistik & Operasional',
                'location_id' => $kendari->id,
                'phone' => '081234567812',
                'email' => 'lina.m@nikel.co.id',
            ],
            [
                'nip' => 'EMP-013',
                'name' => 'Bayu Prakoso',
                'position' => 'Surveyor Tambang',
                'department' => 'Eksplorasi Tambang',
                'location_id' => $obi->id,
                'phone' => '081234567813',
                'email' => 'bayu.p@nikel.co.id',
            ],
            [
                'nip' => 'EMP-014',
                'name' => 'Sari Wulandari',
                'position' => 'Staff Procurement',
                'department' => 'Pengadaan',
                'location_id' => $headOffice->id,
                'phone' => '081234567814',
                'email' => 'sari.w@nikel.co.id',
            ],
            [
                'nip' => 'EMP-015',
                'name' => 'Iwan Setiawan',
                'position' => 'Supervisor HSE',
                'department' => 'HSE & Safety',
                'location_id' => $pomalaa->id,
                'phone' => '081234567815',
                'email' => 'iwan.s@nikel.co.id',
            ],
            [
                'nip' => 'EMP-016',
                'name' => 'Mega Kusuma',
                'position' => 'Staff Operasi',
                'department' => 'Operasi Tambang',
                'location_id' => $palu->id,
                'phone' => '081234567816',
                'email' => 'mega.k@nikel.co.id',
            ],
            [
                'nip' => 'EMP-017',
                'name' => 'Taufik Hidayat',
                'position' => 'Plant Operator',
                'department' => 'Pengolahan Bijih',
                'location_id' => $wedaBay->id,
                'phone' => '081234567817',
                'email' => 'taufik.h@nikel.co.id',
            ],
            [
                'nip' => 'EMP-018',
                'name' => 'Rina Oktaviani',
                'position' => 'Admin Site',
                'department' => 'Logistik & Operasional',
                'location_id' => $morowali->id,
                'phone' => '081234567818',
                'email' => 'rina.o@nikel.co.id',
            ],
            [
                'nip' => 'EMP-019',
                'name' => 'Doni Prasetyo',
                'position' => 'Teknisi Alat Berat',
                'department' => 'Pemeliharaan Armada',
                'location_id' => $sorowako->id,
                'phone' => '081234567819',
                'email' => 'doni.p@nikel.co.id',
            ],
            [
                'nip' => 'EMP-020',
                'name' => 'Yuni Safitri',
                'position' => 'Analis Lingkungan',
                'department' => 'HSE & Safety',
                'location_id' => $konawe->id,
                'phone' => '081234567820',
                'email' => 'yuni.s@nikel.co.id',
            ],
        ];

        foreach ($employees as $emp) {
            Employee::create($emp);
        }
    }
}
