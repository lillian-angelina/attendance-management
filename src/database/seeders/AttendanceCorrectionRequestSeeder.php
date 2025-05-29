<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceCorrectionRequest;

class AttendanceCorrectionRequestSeeder extends Seeder
{
    public function run()
    {
        AttendanceCorrectionRequest::factory()->count(20)->create();
    }
}