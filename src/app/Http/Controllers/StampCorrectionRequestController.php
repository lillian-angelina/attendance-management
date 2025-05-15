<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StampCorrectionRequestController extends Controller
{
    public function index()
    {
        // 仮の申請データ（通常はDBから取得）
        $requests = [
            [
                'id' => 1,
                'status' => '承認待ち',
                'name' => '山田 太郎',
                'target_date' => '2025-05-01',
                'reason' => '打刻漏れのため',
                'requested_at' => '2025-05-02 10:15:00',
            ],
            [
                'id' => 2,
                'status' => '承認済み',
                'name' => '佐藤 花子',
                'target_date' => '2025-04-30',
                'reason' => '出勤時間修正',
                'requested_at' => '2025-05-01 09:00:00',
            ],
        ];

        return view('stamp_correction_request.index', compact('requests'));
    }
}
