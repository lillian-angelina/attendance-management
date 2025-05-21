<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $start = Carbon::parse('09:00');
        $end = Carbon::parse('18:00');
        $break = 60;
        $total = $end->diffInMinutes($start) - $break;

        return [
            'user_id' => User::inRandomOrder()->first()->id, // 既存ユーザーのID
            'work_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
            'work_start' => $start->format('H:i:s'),
            'work_end' => $end->format('H:i:s'),
            'break_time' => $break,
            'total_time' => $total,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}