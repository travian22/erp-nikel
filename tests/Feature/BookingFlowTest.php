<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_booking_and_requires_two_level_approval(): void
    {
        $location = Location::create([
            'name' => 'Site Pomalaa Test',
            'type' => 'tambang',
            'address' => 'Pomalaa Site',
        ]);

        $employee = Employee::create([
            'nip' => 'EMP-TEST-1',
            'name' => 'Pemohon Test',
            'position' => 'Staff Ops',
            'department' => 'Mining',
            'location_id' => $location->id,
            'phone' => '0812345',
            'email' => 'test.pemohon@nikel.co.id',
        ]);

        $category = VehicleCategory::create([
            'name' => 'Angkutan Orang',
            'description' => 'Test Cat',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'DT 9999 TEST',
            'brand' => 'Toyota',
            'model' => 'Hilux',
            'year' => 2024,
            'category_id' => $category->id,
            'ownership_type' => 'milik_sendiri',
            'location_id' => $location->id,
            'status' => 'tersedia',
            'capacity' => 5,
            'fuel_type' => 'Dexlite',
        ]);

        $driver = Driver::create([
            'name' => 'Driver Test',
            'license_number' => 'SIM-B2-123',
            'license_expiry' => '2028-01-01',
            'phone' => '0812345678',
            'location_id' => $location->id,
            'status' => 'available',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $approver1 = User::factory()->create([
            'role' => 'approver',
            'username' => 'approver_l1',
        ]);

        $approver2 = User::factory()->create([
            'role' => 'approver',
            'username' => 'approver_l2',
        ]);

        // 1. Admin submits booking request
        $response = $this->actingAs($admin)->post('/bookings-store', [
            'requester_id' => $employee->id,
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'purpose' => 'Survei Area Tambang Baru',
            'destination' => 'PIT 5',
            'start_datetime' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'end_datetime' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            'passenger_or_load_qty' => 3,
            'approver_level_1_id' => $approver1->id,
            'approver_level_2_id' => $approver2->id,
        ]);

        $response->assertRedirect(route('bookings.index'));

        $booking = Booking::latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals('menunggu_persetujuan', $booking->status);

        // Assert 2 Level Approvals created
        $this->assertCount(2, $booking->approvals);
        $l1 = $booking->approvals()->where('approval_level', 1)->first();
        $l2 = $booking->approvals()->where('approval_level', 2)->first();

        $this->assertEquals($approver1->id, $l1->approver_id);
        $this->assertEquals($approver2->id, $l2->approver_id);
        $this->assertEquals('menunggu', $l1->status);
        $this->assertEquals('menunggu', $l2->status);

        // Verify Audit Log auto-generated
        $this->assertDatabaseHas('application_logs', [
            'module' => 'Pemesanan',
            'reference_table' => 'bookings',
            'reference_id' => $booking->id,
        ]);

        // 2. Approver L1 Approves
        $this->actingAs($approver1)->post("/approvals/{$l1->id}/approve", [
            'notes' => 'Disetujui Level 1 OK',
        ]);

        $l1->refresh();
        $booking->refresh();

        $this->assertEquals('disetujui', $l1->status);
        $this->assertEquals('menunggu_persetujuan', $booking->status); // Still waiting for L2

        // 3. Approver L2 Approves
        $this->actingAs($approver2)->post("/approvals/{$l2->id}/approve", [
            'notes' => 'Disetujui Level 2 Final OK',
        ]);

        $l2->refresh();
        $booking->refresh();
        $vehicle->refresh();
        $driver->refresh();

        $this->assertEquals('disetujui', $l2->status);
        $this->assertEquals('disetujui', $booking->status); // Full Approval!
        $this->assertEquals('digunakan', $vehicle->status);
        $this->assertEquals('on_duty', $driver->status);
    }
}
