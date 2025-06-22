<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\AttendanceCorrectionRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    protected $model = Attendance::class;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendance_breaks')->truncate();
        DB::table('attendances')->truncate();
        User::where('id', '!=', 1)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $testUser = User::where('id', 1)->first();
        if ($testUser) {
            $this->createAttendanceData($testUser);
        }

        $users = collect();
        for ($i = 0; $i < 20; $i++) {
            $users->push(User::factory()->create([
                'name' => fake('ja_JP')->name(),
                'email' => fake('ja_JP')->unique()->safeEmail(),
            ]));
        }

        foreach ($users as $user) {
            $this->createAttendanceData($user);
        }
    }

    private function createAttendanceData(User $user): void
    {
        $statusOptions = ['pending', 'approved'];
        $reasons = [
            '打刻忘れのため修正を希望します。',
            '出勤時刻に誤りがあります。',
            '退勤が正しく記録されていません。',
            '休憩時間の修正をお願いします。',
            '外出時間が反映されていません。'
        ];

        $daysAdded = 0;
        $date = Carbon::create(2025, 4, 1);

        while ($daysAdded < 40) {
            if ($date->isSaturday() || $date->isSunday()) {
                $date->addDay();
                continue;
            }

            $workStart = Carbon::create($date->year, $date->month, $date->day, 9, 0, 0);   // 09:00
            $workEnd = Carbon::create($date->year, $date->month, $date->day, 18, 0, 0);    // 18:00
            $breakStart = Carbon::create($date->year, $date->month, $date->day, 12, 0, 0);    // 12:00
            $breakEnd = Carbon::create($date->year, $date->month, $date->day, 13, 0, 0);    // 13:00

            $breakMinutes = abs($breakStart->diffInMinutes($breakEnd));
            $totalMinutes = abs($workStart->diffInMinutes($workEnd)) - $breakMinutes;

            $attendance = Attendance::create([
                'user_id' => $user->id,
                'work_date' => $date->toDateString(),
                'work_start' => $workStart,
                'work_end' => $workEnd,
                'break_time' => $breakMinutes,
                'total_time' => $totalMinutes,
                'status' => $statusOptions[array_rand($statusOptions)],
                'target_date' => $date->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            AttendanceBreak::create([
                'attendance_id' => $attendance->id,
                'rest_start_time' => $breakStart->format('H:i'),
                'rest_end_time' => $breakEnd->format('H:i'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            AttendanceCorrectionRequest::create([
                'attendance_id' => $attendance->id,
                'user_id' => $user->id,
                'status' => $attendance->status,
                'target_date' => $attendance->target_date,
                'reason' => $reasons[array_rand($reasons)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $date->addDay();
            $daysAdded++;
        }
    }
}