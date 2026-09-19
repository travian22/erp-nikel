<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\RentalCompany;
use App\Models\User;
use App\Models\VehicleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_manage_rental_companies()
    {
        // 1. Index
        $response = $this->actingAs($this->admin)->get(route('rental-companies.index'));
        $response->assertStatus(200);

        // 2. Store
        $response = $this->actingAs($this->admin)->post(route('rental-companies.store'), [
            'name' => 'PT Rental Utama',
            'contact_person' => 'Budi',
            'phone' => '0812345678',
            'address' => 'Jakarta',
        ]);
        $response->assertRedirect(route('rental-companies.index'));
        $this->assertDatabaseHas('rental_companies', ['name' => 'PT Rental Utama']);

        $rental = RentalCompany::where('name', 'PT Rental Utama')->first();

        // 3. Update
        $response = $this->actingAs($this->admin)->put(route('rental-companies.update', $rental), [
            'name' => 'PT Rental Utama Perdana',
            'contact_person' => 'Budi S',
        ]);
        $response->assertRedirect(route('rental-companies.index'));
        $this->assertDatabaseHas('rental_companies', ['name' => 'PT Rental Utama Perdana']);

        // 4. Destroy
        $response = $this->actingAs($this->admin)->delete(route('rental-companies.destroy', $rental));
        $response->assertRedirect(route('rental-companies.index'));
        $this->assertDatabaseMissing('rental_companies', ['id' => $rental->id]);
    }

    public function test_admin_can_manage_locations()
    {
        // 1. Index
        $response = $this->actingAs($this->admin)->get(route('locations.index'));
        $response->assertStatus(200);

        // 2. Store
        $response = $this->actingAs($this->admin)->post(route('locations.store'), [
            'name' => 'Pool Tambang Alpha',
            'type' => 'tambang',
            'address' => 'Morowali',
        ]);
        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseHas('locations', ['name' => 'Pool Tambang Alpha']);

        $location = Location::where('name', 'Pool Tambang Alpha')->first();

        // 3. Update
        $response = $this->actingAs($this->admin)->put(route('locations.update', $location), [
            'name' => 'Pool Tambang Alpha 1',
            'type' => 'tambang',
        ]);
        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseHas('locations', ['name' => 'Pool Tambang Alpha 1']);

        // 4. Destroy
        $response = $this->actingAs($this->admin)->delete(route('locations.destroy', $location));
        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }

    public function test_admin_can_manage_vehicle_categories()
    {
        // 1. Index
        $response = $this->actingAs($this->admin)->get(route('categories.index'));
        $response->assertStatus(200);

        // 2. Store
        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'name' => 'Heavy Hauler',
            'description' => 'Truk angkut material berat',
        ]);
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('vehicle_categories', ['name' => 'Heavy Hauler']);

        $category = VehicleCategory::where('name', 'Heavy Hauler')->first();

        // 3. Update
        $response = $this->actingAs($this->admin)->put(route('categories.update', $category), [
            'name' => 'Heavy Hauler Special',
            'description' => 'Truk angkut material berat khusus',
        ]);
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('vehicle_categories', ['name' => 'Heavy Hauler Special']);

        // 4. Destroy
        $response = $this->actingAs($this->admin)->delete(route('categories.destroy', $category));
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('vehicle_categories', ['id' => $category->id]);
    }
}
