<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PermissionTestingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_testing_page_1',
            'create_testing_page_1',
            'edit_testing_page_1', 
            'delete_testing_page_1',
            
            'view_testing_page_2',
            'create_testing_page_2',
            'edit_testing_page_2',
            'delete_testing_page_2',
            
            'view_testing_page_3', 
            'create_testing_page_3',
            'edit_testing_page_3',
            'delete_testing_page_3',
        
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        
        $employee2 = User::create([
            'name' => 'Employee 02',
            'email' => 'employee02@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'remember_token' => Str::random(60),
        ]);

        $employee3 = User::create([
            'name' => 'Employee 03',
            'email' => 'employee03@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'remember_token' => Str::random(60),
        ]);

        $employee4 = User::create([
            'name' => 'Employee 04',
            'email' => 'employee04@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789',
            'remember_token' => Str::random(60),
        ]);

        $employee2->assignRole($employeeRole);
        $employee2->givePermissionTo(['create_testing_page_1', 'delete_testing_page_1']);

        $employee3->assignRole($employeeRole);
        $employee3->givePermissionTo(['view_testing_page_2', 'edit_testing_page_2']);

        $employee4->assignRole($employeeRole);
        $employee4->givePermissionTo(['view_testing_page_3']);

        $this->command->info('Permissions and roles created successfully!');


    }
}
