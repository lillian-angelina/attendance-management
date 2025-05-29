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
        $testUser = User::find(1);
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
        $date = Carbon::now()->startOfMonth();

        while ($daysAdded < 15) {
            if ($date->isSaturday() || $date->isSunday()) {
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
                'work_date' => $date,
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