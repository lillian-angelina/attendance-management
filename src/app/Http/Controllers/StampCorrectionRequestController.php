<?php

// StampCorrectionRequestController.php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectionRequest;
use App\Models\AttendanceBreak;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Http\Requests\AttendanceCorrectionRequestRequest;



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
    public function store(AttendanceCorrectionRequestRequest $request)
    {
        $correctionRequest = AttendanceCorrectionRequest::create([
            'user_id' => Auth::id(),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'note' => $request->input('note'),
            'status' => 'pending',
            'requested_at' => Carbon::now(),
        ]);

        if ($request->has('breaks')) {
            foreach ($request->input('breaks') as $break) {
                AttendanceBreak::create([
                    'attendance_correction_request_id' => $correctionRequest->id,
                    'rest_start_time' => $break['rest_start_time'],
                    'rest_end_time' => $break['rest_end_time'],
                ]);
            }
        }

        return redirect()->route('stamp_correction_request.index')->with('success', '修正申請を送信しました。');
    }
}
