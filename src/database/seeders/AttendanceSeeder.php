<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 日本人ユーザーを20人作成
        $users = User::factory()->count(20)->create([
            'name' => fn() => fake('ja_JP')->name(),
            'email' => fn() => fake('ja_JP')->unique()->safeEmail(),
        ]);

        foreach ($users as $user) {
            for ($i = 0; $i < 15; $i++) {
                // 1ヶ月分くらいの平日だけ
                $date = Carbon::now()->startOfMonth()->addDays($i);

                // 出勤: 9:00、退勤: 18:00、休憩: 12:00〜13:00（固定）
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
            }
        }
    }
}
