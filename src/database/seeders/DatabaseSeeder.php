<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\TestUserSeeder;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TestUserSeeder::class);

        $this->call(AttendanceSeeder::class);

        $this->call(AdminSeeder::class);
    }
}
