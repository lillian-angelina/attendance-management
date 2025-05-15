<?php

// StampCorrectionRequestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceRequest;

class StampCorrectionRequestController extends Controller
{
    public function index()
    {
        // 仮の申請データ（実際はDBから取得する）
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

    // ★ 申請保存処理を追加
    public function store(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_time' => 'required|string|max:10',
            'note' => 'nullable|string|max:255',
        ]);

        AttendanceRequest::create([
            'attendance_id' => $id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_time' => $request->break_time,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', '修正申請を送信しました。');
    }
}
