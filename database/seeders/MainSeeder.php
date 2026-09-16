<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'IT',
            'description' => 'ITTE',
        ]);

        Category::create([
            'name' => 'IT',
            'description' => 'ITTE',
        ]);

        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'department_id' => '1',
        //     'password' => Hash::make('admin123'),
        // ]);
    }
}
