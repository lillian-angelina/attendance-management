<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceCorrectionRequest;

class AttendanceCorrectionRequestSeeder extends Seeder
{
    protected $model = AttendanceCorrectionReques::class;

    public function run()
    {
        // 既存のレコードを削除
        AttendanceCorrectionRequest::truncate();

        // ランダムなダミーデータを20件生成
        AttendanceCorrectionRequest::factory()->count(20)->create();
    }
}