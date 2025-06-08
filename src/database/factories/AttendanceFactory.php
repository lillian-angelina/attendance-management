<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $reasons = [
            '打刻忘れのため修正を希望します。',
            '出勤時刻に誤りがあります。',
            '退勤が正しく記録されていません。',
            '休憩時間の修正をお願いします。',
            '外出時間が反映されていません。'
        ];
        $date = now()->subDays(rand(1, 30))->startOfDay();

        $start = Carbon::parse('09:00')->addMinutes(rand(-30, 30)); // 8:30～9:30
        $end = (clone $start)->addHours(8)->addMinutes(rand(-15, 15)); // 8時間 ±15分
        $break = 60; // 昼休憩固定
        $total = $end->diffInMinutes($start) - $break;

        return [
            'work_date' => $date->format('Y-m-d'),
            'work_start' => $start->format('H:i:s'),
            'work_end' => $end->format('H:i:s'),
            'break_time' => $break,
            'total_time' => $total,
            'user_id' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'target_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'reason' => $this->faker->randomElement($reasons),
            'requested_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
