<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendance_breaks')->truncate();
        DB::table('attendances')->truncate();
        User::where('id', '!=', 1)->delete(); // テストユーザー以外を削除
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //  テストユーザーに対して勤怠データ作成
        $testUser = User::where('id', 1)->first();
        if ($testUser) {
            $this->createAttendanceData($testUser);
        }

        //  その他のダミーユーザー20人を作成
        $users = collect();
        for ($i = 0; $i < 20; $i++) {
            $users->push(User::factory()->create([
                'name' => fake('ja_JP')->name(),
                'email' => fake('ja_JP')->unique()->safeEmail(),
            ]));
        }

        //  ダミーユーザーに対して勤怠データ作成
        foreach ($users as $user) {
            $this->createAttendanceData($user);
        }
    }

    //  ユーザーごとの勤怠データ生成処理を共通関数化
    private function createAttendanceData(User $user): void
    {
        $daysAdded = 0;
        $date = Carbon::create(2025, 6, 1); // 初期日付（固定）

        while ($daysAdded < 40) {
            if ($date->isSaturday() || $date->isSunday()) {
                $date->addDay();
                continue;
            }

            // 正確な日時としてCarbonを生成（年月日+時間）
            $workStart = Carbon::create($date->year, $date->month, $date->day, 9, 0, 0);   // 09:00
            $workEnd = Carbon::create($date->year, $date->month, $date->day, 18, 0, 0);    // 18:00
            $breakStart = Carbon::create($date->year, $date->month, $date->day, 12, 0, 0);    // 12:00
            $breakEnd = Carbon::create($date->year, $date->month, $date->day, 13, 0, 0);    // 13:00

            // 正しく正の値を得る
            $breakMinutes = abs($breakStart->diffInMinutes($breakEnd));
            $totalMinutes = abs($workStart->diffInMinutes($workEnd)) - $breakMinutes;

            // 勤怠データ作成（時刻は "H:i" で保存）
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'work_date' => $date->toDateString(),           // DATE型
                'work_start' => $workStart,       // TIME型
                'work_end' => $workEnd,         // TIME型
                'break_time' => $breakMinutes,                   // int
                'total_time' => $totalMinutes,                   // int
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

            $date->addDay();
            $daysAdded++;
        }
    }
}