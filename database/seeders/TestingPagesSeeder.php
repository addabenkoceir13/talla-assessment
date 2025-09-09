<?php
// database/seeders/TestingPagesSeeder.php

namespace Database\Seeders;

use App\Models\TestingData;
use Illuminate\Database\Seeder;
use App\Models\TestingPage1Data;
use App\Models\TestingPage2Data;
use App\Models\TestingPage3Data;
use App\Models\User;

class TestingPagesSeeder extends Seeder
{
    public function run(): void
    {

        $admin = User::role('admin')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $this->createTestingPageData($admin->id);

        $this->command->info('Testing pages data created successfully!');
    }

    private function createTestingPageData(int $adminId): void
    {
        $tasks = [
            [
                'title' => 'Website Redesign Project',
                'description' => 'Complete overhaul of the company website with modern UI/UX design',
                'status' => 'active',
                'priority' => 'high',
                'category' => 'Development',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
            [
                'title' => 'Database Backup Setup',
                'description' => 'Implement automated daily backup system for all databases',
                'status' => 'pending',
                'priority' => 'urgent',
                'category' => 'IT Infrastructure',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
            [
                'title' => 'Employee Training Program',
                'description' => 'Develop comprehensive training materials for new employees',
                'status' => 'active',
                'priority' => 'medium',
                'category' => 'Human Resources',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
            [
                'title' => 'Security Audit',
                'description' => 'Conduct thorough security assessment of all systems',
                'status' => 'inactive',
                'priority' => 'high',
                'category' => 'Security',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
            [
                'title' => 'Marketing Campaign Analysis',
                'description' => 'Analyze the effectiveness of recent marketing campaigns',
                'status' => 'active',
                'priority' => 'low',
                'category' => 'Marketing',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
            [
                'title' => 'Software License Renewal',
                'description' => 'Renew expiring software licenses for all departments',
                'status' => 'pending',
                'priority' => 'medium',
                'category' => 'Administration',
                'created_by' => $adminId,
                'page_type' => 'page1',
            ],
             [
                'title' => 'Website Redesign Project',
                'description' => 'Complete overhaul of the company website with modern UI/UX design',
                'status' => 'active',
                'priority' => 'high',
                'category' => 'Development',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
            [
                'title' => 'Database Backup Setup',
                'description' => 'Implement automated daily backup system for all databases',
                'status' => 'pending',
                'priority' => 'urgent',
                'category' => 'IT Infrastructure',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
            [
                'title' => 'Employee Training Program',
                'description' => 'Develop comprehensive training materials for new employees',
                'status' => 'active',
                'priority' => 'medium',
                'category' => 'Human Resources',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
            [
                'title' => 'Security Audit',
                'description' => 'Conduct thorough security assessment of all systems',
                'status' => 'inactive',
                'priority' => 'high',
                'category' => 'Security',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
            [
                'title' => 'Marketing Campaign Analysis',
                'description' => 'Analyze the effectiveness of recent marketing campaigns',
                'status' => 'active',
                'priority' => 'low',
                'category' => 'Marketing',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
            [
                'title' => 'Software License Renewal',
                'description' => 'Renew expiring software licenses for all departments',
                'status' => 'pending',
                'priority' => 'medium',
                'category' => 'Administration',
                'created_by' => $adminId,
                'page_type' => 'page2',
            ],
             [
                'title' => 'Website Redesign Project',
                'description' => 'Complete overhaul of the company website with modern UI/UX design',
                'status' => 'active',
                'priority' => 'high',
                'category' => 'Development',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
            [
                'title' => 'Database Backup Setup',
                'description' => 'Implement automated daily backup system for all databases',
                'status' => 'pending',
                'priority' => 'urgent',
                'category' => 'IT Infrastructure',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
            [
                'title' => 'Employee Training Program',
                'description' => 'Develop comprehensive training materials for new employees',
                'status' => 'active',
                'priority' => 'medium',
                'category' => 'Human Resources',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
            [
                'title' => 'Security Audit',
                'description' => 'Conduct thorough security assessment of all systems',
                'status' => 'inactive',
                'priority' => 'high',
                'category' => 'Security',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
            [
                'title' => 'Marketing Campaign Analysis',
                'description' => 'Analyze the effectiveness of recent marketing campaigns',
                'status' => 'active',
                'priority' => 'low',
                'category' => 'Marketing',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
            [
                'title' => 'Software License Renewal',
                'description' => 'Renew expiring software licenses for all departments',
                'status' => 'pending',
                'priority' => 'medium',
                'category' => 'Administration',
                'created_by' => $adminId,
                'page_type' => 'page3',
            ],
        ];

        foreach ($tasks as $task) {
            TestingData::create($task);
        }

        $this->command->info('✅ Testing Page 1 data (Tasks) created');
    }

}