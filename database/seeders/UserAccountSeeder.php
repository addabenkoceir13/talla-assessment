<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // * roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);


        // * create user with role
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'role' => 'admin',
            'remember_token' => Str::random(60),
        ]);

        $userAdmin->assignRole($adminRole);
        $userAdmin->givePermissionTo(Permission::all());

        $userEmployee = User::create([
            'name' => 'Employee',
            'email' => 'employee@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'role' => 'employee',
            'remember_token' => Str::random(60),
        ]);

        $userEmployee->assignRole($employeeRole);
        $userEmployee->givePermissionTo(['create', 'delete']);

        $userEmployee1 = User::create([
            'name' => 'Employee 01',
            'email' => 'employee01@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'remember_token' => Str::random(60),
        ]);

        $userEmployee1->assignRole($employeeRole);
    }
}
