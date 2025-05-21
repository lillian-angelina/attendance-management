<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()->count(20)->create();

        foreach ($users as $user) {
            Attendance::factory()->count(20)->create([
                'user_id' => $user->id,
            ]);
        }

        Attendance::factory()->count(100)->create(); // 100件の勤怠データを生成

        $start = Carbon::createFromTime(9, 0);
        $end = Carbon::createFromTime(18, 0);
        $break = 60; // 分単位

        $total = $end->diffInMinutes($start) - $break;

        Attendance::create([
            'user_id' => 1,
            'work_date' => $user->work_date,
            'work_start' => $start->format('H:i:s'),
            'work_end' => $end->format('H:i:s'),
            'break_time' => $break,
            'total_time' => $total, // 分単位
        ]);
    }
}
