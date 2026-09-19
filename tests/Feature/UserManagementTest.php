<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_user_management_index()
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Akun Pengguna');
    }

    public function test_admin_can_create_new_user()
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Doni Operator',
            'username' => 'doni_op',
            'email' => 'doni@nikel.co.id',
            'password' => 'password123',
            'role' => 'user',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'doni_op',
            'email' => 'doni@nikel.co.id',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_update_existing_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($this->admin)->put(route('users.update', $user), [
            'name' => 'Doni Operator Updated',
            'username' => $user->username,
            'email' => $user->email,
            'role' => 'approver',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Doni Operator Updated',
            'role' => 'approver',
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self()
    {
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
