<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmp = Employee::where('nip', 'EMP-001')->first();
        $app1Emp = Employee::where('nip', 'EMP-002')->first();
        $app2Emp = Employee::where('nip', 'EMP-003')->first();

        // Admin User
        User::create([
            'username' => 'admin',
            'name' => 'Budi Santoso',
            'email' => 'admin@nikel.co.id',
            'password' => 'password', // Auto hashed by model cast
            'role' => 'admin',
            'employee_id' => $adminEmp?->id,
            'is_active' => true,
        ]);

        // Approver Level 1 User
        User::create([
            'username' => 'approver1',
            'name' => 'Ahmad Dahlan',
            'email' => 'approver1@nikel.co.id',
            'password' => 'password',
            'role' => 'approver',
            'employee_id' => $app1Emp?->id,
            'is_active' => true,
        ]);

        // Approver Level 2 User
        User::create([
            'username' => 'approver2',
            'name' => 'Siti Rahmawati',
            'email' => 'approver2@nikel.co.id',
            'password' => 'password',
            'role' => 'approver',
            'employee_id' => $app2Emp?->id,
            'is_active' => true,
        ]);
    }
}
