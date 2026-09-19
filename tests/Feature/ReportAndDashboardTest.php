<?php

namespace Tests\Feature;

use App\Exports\BookingReportExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReportAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_can_be_accessed_by_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas(['totalVehicles', 'availableVehicles', 'usedVehicles']);
    }

    public function test_admin_can_download_excel_report(): void
    {
        Excel::fake();

        $now = now();
        $this->travelTo($now);

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/reports/export');

        $response->assertStatus(200);

        Excel::assertDownloaded('laporan-pemesanan-kendaraan-'.$now->format('Ymd-His').'.xlsx', function (BookingReportExport $export) {
            return true;
        });
    }
}
