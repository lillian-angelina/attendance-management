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
        return [
            'user_id' => $this->faker->numberBetween(1, 5),
            'attendance_id' => $this->faker->numberBetween(1, 20),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'name' => $this->faker->name(),
            'requested_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
