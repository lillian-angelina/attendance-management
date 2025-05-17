<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-1 month', 'now');
        $end = (clone $start)->modify('+8 hours');
        $breakMinutes = $this->faker->numberBetween(30, 90);

        return [
            'work_date' => Carbon::instance($start)->toDateString(),
            'clock_in' => $start,
            'clock_out' => $end,
            'break_time' => $breakMinutes,
            // 'total_time' → コントローラ側で計算する場合は不要
        ];
    }
}
