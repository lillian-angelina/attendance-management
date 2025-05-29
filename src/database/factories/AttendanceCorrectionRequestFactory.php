<?php

namespace Database\Factories;

use App\Models\AttendanceCorrectionRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceCorrectionRequestFactory extends Factory
{
    protected $model = AttendanceCorrectionRequest::class;

    public function definition(): array
    {
        $reasons = [
            '打刻忘れのため修正を希望します。',
            '出勤時刻に誤りがあります。',
            '退勤が正しく記録されていません。',
            '休憩時間の修正をお願いします。',
            '外出時間が反映されていません。'
        ];

        return [
            'user_id' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'target_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'reason' => $this->faker->randomElement($reasons),
            'requested_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
