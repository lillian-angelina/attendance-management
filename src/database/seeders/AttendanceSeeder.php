<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendance::create([
            'user_id' => 1,
            'work_start' => '2025-05-01 09:00:00',
            'work_end' => '2025-05-01 18:00:00',
            'note' => 'テストデータ',
        ]);
    }
}
