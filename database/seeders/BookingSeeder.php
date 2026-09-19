<?php

namespace Database\Seeders;

use App\Models\ApplicationLog;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\FuelLog;
use App\Models\ServiceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUsageHistory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $approver1 = User::where('username', 'approver1')->first();
        $approver2 = User::where('username', 'approver2')->first();

        $employees = Employee::query()->get()->keyBy('nip');
        $vehicles = Vehicle::query()->get()->keyBy('plate_number');
        $drivers = Driver::query()->get()->keyBy('name');

        $period = date('Ym');

        // Booking 1: Menunggu Persetujuan Level 1
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-001',
            'requester_id' => $employees['EMP-004']->id,
            'vehicle_id' => $vehicles['DT 7002 PK']->id,
            'driver_id' => $drivers['Junaedi Mappasomba']->id,
            'purpose' => 'Inspeksi Lapangan PIT 3 Pomalaa',
            'destination' => 'Area Tambang PIT 3 Pomalaa',
            'start_datetime' => Carbon::now()->addDays(1)->setHour(8)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(1)->setHour(17)->setMinute(0),
            'passenger_or_load_qty' => 4,
            'status' => 'menunggu_persetujuan',
            'created_by' => $admin->id,
        ], [
            $this->pendingApproval(1, $approver1),
            $this->pendingApproval(2, $approver2),
        ], [
            [
                'user_id' => $admin->id,
                'activity' => 'Input Pemesanan Kendaraan baru: BOOK-'.$period.'-001',
                'module' => 'Pemesanan',
                'created_at' => Carbon::now()->subHours(2),
            ],
        ]);

        // Booking 2: Disetujui Level 1, Menunggu Level 2
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-002',
            'requester_id' => $employees['EMP-005']->id,
            'vehicle_id' => $vehicles['DN 8003 MR']->id,
            'driver_id' => $drivers['Usman Harun']->id,
            'purpose' => 'Mobilisasi Tim HSE ke Site Bahodopi Morowali',
            'destination' => 'Bahodopi, Morowali',
            'start_datetime' => Carbon::now()->addDays(2)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(3)->setHour(18)->setMinute(0),
            'passenger_or_load_qty' => 5,
            'status' => 'menunggu_persetujuan',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui. Pastikan perlengkapan K3 siap.', Carbon::now()->subHour()),
            $this->pendingApproval(2, $approver2),
        ], [
            [
                'user_id' => $approver1->id,
                'activity' => 'Menyetujui Pemesanan Level 1: BOOK-'.$period.'-002',
                'module' => 'Persetujuan',
                'created_at' => Carbon::now()->subHour(),
            ],
        ]);

        // Booking 3: Selesai (Level 1 & Level 2 Disetujui)
        $b3 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-003',
            'requester_id' => $employees['EMP-004']->id,
            'vehicle_id' => $vehicles['B 1001 NKL']->id,
            'driver_id' => $drivers['Syarifuddin']->id,
            'purpose' => 'Kunjungan Tamu Investor Kementerian ESDM',
            'destination' => 'Kantor Pusat & Site Tambang',
            'start_datetime' => Carbon::now()->subDays(2)->setHour(8)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(1)->setHour(17)->setMinute(0),
            'passenger_or_load_qty' => 4,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(3)),
            $this->approvedApproval(2, $approver2, 'Disetujui Level 2 (Final Approval)', Carbon::now()->subDays(2)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b3->id,
            'start_odometer' => 12500,
            'end_odometer' => 12780,
            'actual_start_time' => Carbon::now()->subDays(2)->setHour(8)->setMinute(15),
            'actual_end_time' => Carbon::now()->subDays(1)->setHour(16)->setMinute(45),
            'notes' => 'Perjalanan lancar tanpa kendala operasional.',
        ]);

        FuelLog::create([
            'vehicle_id' => $vehicles['B 1001 NKL']->id,
            'booking_id' => $b3->id,
            'fuel_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'liters' => 50.00,
            'cost' => 745000.00,
            'odometer' => 12500,
        ]);

        FuelLog::create([
            'vehicle_id' => $vehicles['DT 7002 PK']->id,
            'booking_id' => null,
            'fuel_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'liters' => 65.50,
            'cost' => 975000.00,
            'odometer' => 45200,
        ]);

        // Booking 4: Ditolak Level 1
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-004',
            'requester_id' => $employees['EMP-007']->id,
            'vehicle_id' => $vehicles['DT 7110 PK']->id,
            'driver_id' => $drivers['Wahyu Ningsih']->id,
            'purpose' => 'Antar sampel bijih ke laboratorium Kendari',
            'destination' => 'Lab Mineral Kendari',
            'start_datetime' => Carbon::now()->addDays(4)->setHour(7)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(4)->setHour(20)->setMinute(0),
            'passenger_or_load_qty' => 2,
            'status' => 'ditolak',
            'created_by' => $admin->id,
        ], [
            $this->rejectedApproval(1, $approver1, 'Jadwal bentrok dengan inspeksi PIT. Ajukan ulang minggu depan.', Carbon::now()->subHours(6)),
            $this->pendingApproval(2, $approver2),
        ], [
            [
                'user_id' => $approver1->id,
                'activity' => 'Menolak Pemesanan Level 1: BOOK-'.$period.'-004',
                'module' => 'Persetujuan',
                'created_at' => Carbon::now()->subHours(6),
            ],
        ]);

        // Booking 5: Ditolak Level 2 setelah L1 disetujui
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-005',
            'requester_id' => $employees['EMP-008']->id,
            'vehicle_id' => $vehicles['DG 9110 WB']->id,
            'driver_id' => $drivers['Hendra Gunawan']->id,
            'purpose' => 'Survey geologi blok baru Weda Bay',
            'destination' => 'Blok Eksplorasi Timur Weda',
            'start_datetime' => Carbon::now()->addDays(5)->setHour(6)->setMinute(30),
            'end_datetime' => Carbon::now()->addDays(6)->setHour(18)->setMinute(0),
            'passenger_or_load_qty' => 4,
            'status' => 'ditolak',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Jalur sudah dicek HSE site.', Carbon::now()->subDays(1)),
            $this->rejectedApproval(2, $approver2, 'Cuaca hujan deras, tunda sampai akses jalan aman.', Carbon::now()->subHours(10)),
        ], [
            [
                'user_id' => $approver2->id,
                'activity' => 'Menolak Pemesanan Level 2: BOOK-'.$period.'-005',
                'module' => 'Persetujuan',
                'created_at' => Carbon::now()->subHours(10),
            ],
        ]);

        // Booking 6: Disetujui, sedang berjalan (HO)
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-006',
            'requester_id' => $employees['EMP-014']->id,
            'vehicle_id' => $vehicles['B 1103 NKL']->id,
            'driver_id' => $drivers['Andi Rahman']->id,
            'purpose' => 'Pendampingan audit vendor pengadaan kantor pusat',
            'destination' => 'Kawasan Industri Pulo Gadung',
            'start_datetime' => Carbon::now()->subHours(3)->setMinute(0),
            'end_datetime' => Carbon::now()->addHours(5)->setMinute(0),
            'passenger_or_load_qty' => 3,
            'status' => 'disetujui',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(1)->setHour(9)),
            $this->approvedApproval(2, $approver2, 'Final approval untuk kunjungan vendor.', Carbon::now()->subDays(1)->setHour(14)),
        ]);

        // Booking 7: Disetujui, dump truck Pomalaa
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-007',
            'requester_id' => $employees['EMP-015']->id,
            'vehicle_id' => $vehicles['DT 7112 PK']->id,
            'driver_id' => $drivers['Supriyadi']->id,
            'purpose' => 'Angkut overburden ke disposal PIT 2',
            'destination' => 'Disposal Utara Pomalaa',
            'start_datetime' => Carbon::now()->setHour(5)->setMinute(0),
            'end_datetime' => Carbon::now()->setHour(17)->setMinute(0),
            'passenger_or_load_qty' => 18000,
            'status' => 'disetujui',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Kapasitas muatan sesuai.', Carbon::now()->subDays(2)),
            $this->approvedApproval(2, $approver2, 'Jalankan sesuai shift siang.', Carbon::now()->subDay()),
        ]);

        // Booking 8: Disetujui, dump truck Weda
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-008',
            'requester_id' => $employees['EMP-017']->id,
            'vehicle_id' => $vehicles['DG 9111 WB']->id,
            'driver_id' => $drivers['Yusuf Maluku']->id,
            'purpose' => 'Hauling bijih ke stockpile pelabuhan',
            'destination' => 'Stockpile Pelabuhan Weda',
            'start_datetime' => Carbon::now()->subHours(2),
            'end_datetime' => Carbon::now()->addHours(8),
            'passenger_or_load_qty' => 20000,
            'status' => 'disetujui',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui produksi Weda.', Carbon::now()->subDays(1)),
            $this->approvedApproval(2, $approver2, 'Final approval hauling.', Carbon::now()->subHours(20)),
        ]);

        // Booking 9: Dibatalkan
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-009',
            'requester_id' => $employees['EMP-006']->id,
            'vehicle_id' => $vehicles['DD 1201 MK']->id,
            'driver_id' => $drivers['Hasan Basri']->id,
            'purpose' => 'Antar dokumen kontrak ke mitra Makassar',
            'destination' => 'Pelabuhan Soekarno-Hatta Makassar',
            'start_datetime' => Carbon::now()->addDays(3)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(3)->setHour(15)->setMinute(0),
            'passenger_or_load_qty' => 2,
            'status' => 'dibatalkan',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui awal.', Carbon::now()->subDays(2)),
            $this->pendingApproval(2, $approver2),
        ], [
            [
                'user_id' => $admin->id,
                'activity' => 'Membatalkan pemesanan BOOK-'.$period.'-009',
                'module' => 'Pemesanan',
                'created_at' => Carbon::now()->subHours(4),
            ],
        ]);

        // Booking 10: Menunggu kedua level
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-010',
            'requester_id' => $employees['EMP-012']->id,
            'vehicle_id' => $vehicles['DT 1202 KD']->id,
            'driver_id' => $drivers['La Ode Ridwan']->id,
            'purpose' => 'Kirim suku cadang ke workshop Pomalaa',
            'destination' => 'Workshop Pomalaa',
            'start_datetime' => Carbon::now()->addDays(2)->setHour(6)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(2)->setHour(18)->setMinute(0),
            'passenger_or_load_qty' => 2500,
            'status' => 'menunggu_persetujuan',
            'created_by' => $admin->id,
        ], [
            $this->pendingApproval(1, $approver1),
            $this->pendingApproval(2, $approver2),
        ]);

        // Booking 11: L1 disetujui, L2 menunggu
        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-011',
            'requester_id' => $employees['EMP-010']->id,
            'vehicle_id' => $vehicles['DS 6110 SR']->id,
            'driver_id' => $drivers['Paulus Rante']->id,
            'purpose' => 'Inspeksi revegetasi area bekas tambang',
            'destination' => 'Blok Rehabilitasi Sorowako',
            'start_datetime' => Carbon::now()->addDays(6)->setHour(8)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(6)->setHour(16)->setMinute(0),
            'passenger_or_load_qty' => 4,
            'status' => 'menunggu_persetujuan',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Tim HSE sudah lengkap.', Carbon::now()->subHours(8)),
            $this->pendingApproval(2, $approver2),
        ]);

        // Booking 12-16: Selesai, untuk histori laporan
        $b12 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-012',
            'requester_id' => $employees['EMP-009']->id,
            'vehicle_id' => $vehicles['DN 8110 MR']->id,
            'driver_id' => $drivers['Abdullah Saleh']->id,
            'purpose' => 'Jemput tim kontraktor smelter Bahodopi',
            'destination' => 'Kawasan Industri Morowali',
            'start_datetime' => Carbon::now()->subDays(8)->setHour(7)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(8)->setHour(19)->setMinute(0),
            'passenger_or_load_qty' => 6,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(10)),
            $this->approvedApproval(2, $approver2, 'Disetujui Level 2', Carbon::now()->subDays(9)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b12->id,
            'start_odometer' => 34210,
            'end_odometer' => 34580,
            'actual_start_time' => Carbon::now()->subDays(8)->setHour(7)->setMinute(20),
            'actual_end_time' => Carbon::now()->subDays(8)->setHour(18)->setMinute(40),
            'notes' => 'Penjemputan sesuai jadwal kapal.',
        ]);

        $b13 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-013',
            'requester_id' => $employees['EMP-013']->id,
            'vehicle_id' => $vehicles['DG 4110 OB']->id,
            'driver_id' => $drivers['Salim Daeng']->id,
            'purpose' => 'Survey titik bor baru Pulau Obi',
            'destination' => 'Kamp Drill Pad Obi Utara',
            'start_datetime' => Carbon::now()->subDays(12)->setHour(6)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(11)->setHour(17)->setMinute(0),
            'passenger_or_load_qty' => 5,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(14)),
            $this->approvedApproval(2, $approver2, 'Disetujui Level 2', Carbon::now()->subDays(13)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b13->id,
            'start_odometer' => 22100,
            'end_odometer' => 22640,
            'actual_start_time' => Carbon::now()->subDays(12)->setHour(6)->setMinute(10),
            'actual_end_time' => Carbon::now()->subDays(11)->setHour(16)->setMinute(50),
            'notes' => 'Akses jalan basah, kecepatan dibatasi.',
        ]);

        $b14 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-014',
            'requester_id' => $employees['EMP-011']->id,
            'vehicle_id' => $vehicles['DT 5110 KN']->id,
            'driver_id' => $drivers['Arman Jaya']->id,
            'purpose' => 'Pengambilan suku cadang dump truck dari Kendari',
            'destination' => 'Gudang Suku Cadang Kendari',
            'start_datetime' => Carbon::now()->subDays(6)->setHour(5)->setMinute(30),
            'end_datetime' => Carbon::now()->subDays(6)->setHour(21)->setMinute(0),
            'passenger_or_load_qty' => 3,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(7)),
            $this->approvedApproval(2, $approver2, 'Disetujui Level 2', Carbon::now()->subDays(7)->addHours(3)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b14->id,
            'start_odometer' => 18750,
            'end_odometer' => 19220,
            'actual_start_time' => Carbon::now()->subDays(6)->setHour(5)->setMinute(45),
            'actual_end_time' => Carbon::now()->subDays(6)->setHour(20)->setMinute(15),
            'notes' => 'Suku cadang lengkap, tidak ada damage report.',
        ]);

        $b15 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-015',
            'requester_id' => $employees['EMP-016']->id,
            'vehicle_id' => $vehicles['DN 1303 PL']->id,
            'driver_id' => $drivers['Irfan Mahmud']->id,
            'purpose' => 'Kirim material camp ke gudang Palu',
            'destination' => 'Gudang Cabang Palu',
            'start_datetime' => Carbon::now()->subDays(15)->setHour(8)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(15)->setHour(16)->setMinute(0),
            'passenger_or_load_qty' => 3200,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui Level 1', Carbon::now()->subDays(17)),
            $this->approvedApproval(2, $approver2, 'Disetujui Level 2', Carbon::now()->subDays(16)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b15->id,
            'start_odometer' => 41020,
            'end_odometer' => 41210,
            'actual_start_time' => Carbon::now()->subDays(15)->setHour(8)->setMinute(5),
            'actual_end_time' => Carbon::now()->subDays(15)->setHour(15)->setMinute(40),
            'notes' => 'Muatan tertutup terpal, diterima gudang.',
        ]);

        $b16 = $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-016',
            'requester_id' => $employees['EMP-018']->id,
            'vehicle_id' => $vehicles['DN 8006 Bus']->id,
            'driver_id' => $drivers['Abdullah Saleh']->id,
            'purpose' => 'Antar pulang shift malam kru smelter',
            'destination' => 'Mess Karyawan Bahodopi',
            'start_datetime' => Carbon::now()->subDays(4)->setHour(22)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(3)->setHour(1)->setMinute(30),
            'passenger_or_load_qty' => 22,
            'status' => 'selesai',
            'created_by' => $admin->id,
        ], [
            $this->approvedApproval(1, $approver1, 'Disetujui shift malam.', Carbon::now()->subDays(5)),
            $this->approvedApproval(2, $approver2, 'Final approval crew bus.', Carbon::now()->subDays(5)->addHours(2)),
        ]);

        VehicleUsageHistory::create([
            'booking_id' => $b16->id,
            'start_odometer' => 9800,
            'end_odometer' => 9915,
            'actual_start_time' => Carbon::now()->subDays(4)->setHour(22)->setMinute(10),
            'actual_end_time' => Carbon::now()->subDays(3)->setHour(1)->setMinute(20),
            'notes' => 'Semua kru sampai mess tanpa insiden.',
        ]);

        $this->storeBooking([
            'booking_code' => 'BOOK-'.$period.'-017',
            'requester_id' => $employees['EMP-020']->id,
            'vehicle_id' => $vehicles['B 1102 NKL']->id,
            'driver_id' => $drivers['Syarifuddin']->id,
            'purpose' => 'Koordinasi pemantauan lingkungan ke KLHK',
            'destination' => 'Kantor KLHK Jakarta',
            'start_datetime' => Carbon::now()->addDays(8)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(8)->setHour(14)->setMinute(0),
            'passenger_or_load_qty' => 2,
            'status' => 'menunggu_persetujuan',
            'created_by' => $admin->id,
        ], [
            $this->pendingApproval(1, $approver1),
            $this->pendingApproval(2, $approver2),
        ]);

        $this->storeFuelLogs($vehicles, $b12, $b13, $b14, $b15, $b16);
        $this->storeServiceSchedules($vehicles);
    }

    /**
     * @param  array<string, mixed>  $booking
     * @param  list<array<string, mixed>>  $approvals
     * @param  list<array<string, mixed>>  $logs
     */
    private function storeBooking(array $booking, array $approvals, array $logs = []): Booking
    {
        $record = Booking::create($booking);

        foreach ($approvals as $approval) {
            BookingApproval::create([
                'booking_id' => $record->id,
                ...$approval,
            ]);
        }

        foreach ($logs as $log) {
            ApplicationLog::create([
                'reference_table' => 'bookings',
                'reference_id' => $record->id,
                'ip_address' => '127.0.0.1',
                ...$log,
            ]);
        }

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    private function pendingApproval(int $level, User $approver): array
    {
        return [
            'approval_level' => $level,
            'approver_id' => $approver->id,
            'status' => 'menunggu',
            'notes' => null,
            'approved_at' => null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function approvedApproval(int $level, User $approver, string $notes, Carbon $approvedAt): array
    {
        return [
            'approval_level' => $level,
            'approver_id' => $approver->id,
            'status' => 'disetujui',
            'notes' => $notes,
            'approved_at' => $approvedAt,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function rejectedApproval(int $level, User $approver, string $notes, Carbon $approvedAt): array
    {
        return [
            'approval_level' => $level,
            'approver_id' => $approver->id,
            'status' => 'ditolak',
            'notes' => $notes,
            'approved_at' => $approvedAt,
        ];
    }

    /**
     * @param  Collection<string, Vehicle>  $vehicles
     */
    private function storeFuelLogs($vehicles, Booking $b12, Booking $b13, Booking $b14, Booking $b15, Booking $b16): void
    {
        $rows = [
            [
                'vehicle_id' => $vehicles['DN 8110 MR']->id,
                'booking_id' => $b12->id,
                'fuel_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'liters' => 72.00,
                'cost' => 1072800.00,
                'odometer' => 34210,
            ],
            [
                'vehicle_id' => $vehicles['DG 4110 OB']->id,
                'booking_id' => $b13->id,
                'fuel_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'liters' => 80.00,
                'cost' => 1192000.00,
                'odometer' => 22100,
            ],
            [
                'vehicle_id' => $vehicles['DT 5110 KN']->id,
                'booking_id' => $b14->id,
                'fuel_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'liters' => 55.25,
                'cost' => 823225.00,
                'odometer' => 18750,
            ],
            [
                'vehicle_id' => $vehicles['DN 1303 PL']->id,
                'booking_id' => $b15->id,
                'fuel_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'liters' => 90.00,
                'cost' => 1260000.00,
                'odometer' => 41020,
            ],
            [
                'vehicle_id' => $vehicles['DN 8006 Bus']->id,
                'booking_id' => $b16->id,
                'fuel_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'liters' => 48.00,
                'cost' => 672000.00,
                'odometer' => 9800,
            ],
            [
                'vehicle_id' => $vehicles['DG 9004 WB']->id,
                'booking_id' => null,
                'fuel_date' => Carbon::now()->subMonth()->format('Y-m-d'),
                'liters' => 210.00,
                'cost' => 2940000.00,
                'odometer' => 67340,
            ],
            [
                'vehicle_id' => $vehicles['DT 7005 Tanker']->id,
                'booking_id' => null,
                'fuel_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'liters' => 180.00,
                'cost' => 2520000.00,
                'odometer' => 51200,
            ],
            [
                'vehicle_id' => $vehicles['DT 7112 PK']->id,
                'booking_id' => null,
                'fuel_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'liters' => 240.00,
                'cost' => 3360000.00,
                'odometer' => 80110,
            ],
            [
                'vehicle_id' => $vehicles['B 1103 NKL']->id,
                'booking_id' => null,
                'fuel_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'liters' => 42.00,
                'cost' => 625800.00,
                'odometer' => 28900,
            ],
            [
                'vehicle_id' => $vehicles['DS 6110 SR']->id,
                'booking_id' => null,
                'fuel_date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'liters' => 60.00,
                'cost' => 894000.00,
                'odometer' => 15440,
            ],
        ];

        foreach ($rows as $row) {
            FuelLog::create($row);
        }
    }

    /**
     * @param  Collection<string, Vehicle>  $vehicles
     */
    private function storeServiceSchedules($vehicles): void
    {
        $rows = [
            [
                'vehicle_id' => $vehicles['DT 7002 PK']->id,
                'service_type' => 'Servis Berkala 45.000 KM & Ganti Oli',
                'scheduled_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 2500000.00,
                'notes' => 'Jadwal rutin bengkel resmi Toyota',
                'status' => 'terjadwal',
            ],
            [
                'vehicle_id' => $vehicles['DT 7005 Tanker']->id,
                'service_type' => 'Perbaikan pompa dan seal tangki BBM',
                'scheduled_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 8500000.00,
                'notes' => 'Unit masuk workshop sampai uji kebocoran selesai.',
                'status' => 'terjadwal',
            ],
            [
                'vehicle_id' => $vehicles['DT 7111 PK']->id,
                'service_type' => 'Ganti kampas rem dan tune up',
                'scheduled_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 1750000.00,
                'notes' => 'Menunggu suku cadang dari Makassar.',
                'status' => 'terjadwal',
            ],
            [
                'vehicle_id' => $vehicles['DN 8112 MR']->id,
                'service_type' => 'Overhaul mesin Dutro',
                'scheduled_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 14500000.00,
                'notes' => 'Antrian bengkel Hino Morowali.',
                'status' => 'terjadwal',
            ],
            [
                'vehicle_id' => $vehicles['B 1001 NKL']->id,
                'service_type' => 'Servis 10.000 KM',
                'scheduled_date' => Carbon::now()->subDays(40)->format('Y-m-d'),
                'completed_date' => Carbon::now()->subDays(38)->format('Y-m-d'),
                'cost' => 1850000.00,
                'notes' => 'Selesai di bengkel Auto2000.',
                'status' => 'selesai',
            ],
            [
                'vehicle_id' => $vehicles['DN 8003 MR']->id,
                'service_type' => 'Ganti filter solar dan oli gardan',
                'scheduled_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'completed_date' => Carbon::now()->subDays(19)->format('Y-m-d'),
                'cost' => 2100000.00,
                'notes' => 'Dikerjakan vendor sewa di site.',
                'status' => 'selesai',
            ],
            [
                'vehicle_id' => $vehicles['DG 9004 WB']->id,
                'service_type' => 'Servis dump body dan hidrolik',
                'scheduled_date' => Carbon::now()->subDays(18)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 6200000.00,
                'notes' => 'Jadwal terlewat karena unit masih hauling.',
                'status' => 'terlewat',
            ],
            [
                'vehicle_id' => $vehicles['DN 8006 Bus']->id,
                'service_type' => 'Cek AC dan kelistrikan kabin',
                'scheduled_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'completed_date' => null,
                'cost' => 950000.00,
                'notes' => 'Ditunda karena bus dipakai shift malam.',
                'status' => 'terlewat',
            ],
        ];

        foreach ($rows as $row) {
            ServiceSchedule::create($row);
        }
    }
}
