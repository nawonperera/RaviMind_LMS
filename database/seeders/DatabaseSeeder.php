<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //$this->call(AdminSeeder::class); // Seed the AdminSeeder to create an admin user
        $this->call(UserSeeder::class);  // Seed the UserSeeder to create instructor and student users)
    }
}
