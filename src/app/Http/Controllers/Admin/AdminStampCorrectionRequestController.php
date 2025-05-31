<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Http\Requests\AdminAttendanceCorrectionApproveRequest;

class AdminStampCorrectionRequestController extends Controller
{
    // 一覧表示
    public function index()
    {
        $requests = AttendanceCorrectionRequest::with('user')
            ->orderBy('requested_at', 'desc')
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'name' => $request->user->name,
                    'target_date' => $request->date,
                    'reason' => $request->note,
                    'status' => $request->status === 'approved' ? '承認済み' : '承認待ち',
                    'requested_at' => $request->requested_at,
                ];
            });

        return view('stamp_correction_request.index', compact('requests'));
    }

    // 詳細表示（承認画面）
    public function showApprove(AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        $attendanceCorrectionRequest = AttendanceCorrectionRequest::with('attendance.breaks', 'user')->findOrFail($attendanceCorrectionRequest->id);
        $attendance = $attendanceCorrectionRequest->attendance;

        if (!$attendance) {
            abort(404, '該当する勤怠データが存在しません。');
        }

        $date = \Carbon\Carbon::parse($attendance->work_date);
        $year = $date->format('Y');
        $monthDay = $date->format('m月d日');

        return view('stamp_correction_request.approve', [
            'attendanceCorrectionRequest' => $attendanceCorrectionRequest,
            'attendance' => $attendance,
            'breaks' => $attendance?->breaks ?? [],
            'year' => $year,
            'monthDay' => $monthDay,
            'correctionReason' => $attendanceCorrectionRequest->reason,
        ]);
    }

    // 承認処理
    public function approve(AdminAttendanceCorrectionApproveRequest $request, AttendanceCorrectionRequest $attendance_correct_request)
    {
        $attendanceRequest = $attendance_correct_request;
        $attendance = $attendanceRequest->attendance;

        // 申請データを更新
        $attendanceRequest->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'admin_id' => auth()->id(),
            'note' => $request->input('note'), // 備考も保存するなら
        ]);

        // 勤怠データ自体も修正するなら
        if ($attendance) {
            $attendance->update([
                'work_start' => $request->input('work_start'),
                'work_end' => $request->input('work_end'),
            ]);

            // 休憩も必要なら更新（ループや削除→再登録が一般的）
        }

        return redirect()->route('admin.stamp_correction_request.index')->with('success', '申請を承認しました。');

    }
}
