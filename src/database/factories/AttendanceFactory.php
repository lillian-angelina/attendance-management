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
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
