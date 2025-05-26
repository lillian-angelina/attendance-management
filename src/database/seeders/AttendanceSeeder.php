<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 日本人ユーザー20人作成
        $users = collect();
        for ($i = 0; $i < 20; $i++) {
            $users->push(User::factory()->create([
                'name' => fake('ja_JP')->name(),
                'email' => fake('ja_JP')->unique()->safeEmail(),
            ]));
        }

        foreach ($users as $user) {
            $daysAdded = 0;
            $date = Carbon::now()->startOfMonth();

            while ($daysAdded < 15) {
                // 土日スキップ
                if ($date->isWeekend()) {
                    $date->addDay();
                    continue;
                }

                $workStart = $date->copy()->setTime(9, 0);
                $workEnd = $date->copy()->setTime(18, 0);
                $breakStart = $date->copy()->setTime(12, 0);
                $breakEnd = $date->copy()->setTime(13, 0);

                $breakMinutes = $breakEnd->diffInMinutes($breakStart);
                $totalMinutes = $workEnd->diffInMinutes($workStart) - $breakMinutes;

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'work_start' => $workStart,
                    'work_end' => $workEnd,
                    'total_time' => $totalMinutes,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                AttendanceBreak::create([
                    'attendance_id' => $attendance->id,
                    'rest_start_time' => $breakStart,
                    'rest_end_time' => $breakEnd,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $date->addDay();
                $daysAdded++;
            }
        }
    }
}
