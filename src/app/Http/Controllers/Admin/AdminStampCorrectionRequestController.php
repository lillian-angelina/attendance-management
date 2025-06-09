<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Http\Requests\AdminAttendanceCorrectionApproveRequest;
use Carbon\Carbon;

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

    // 承認画面表示
    public function showApprove(AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        // 必要なリレーションを取得
        $attendanceCorrectionRequest->load(['attendance.breaks', 'user']);

        $attendance = $attendanceCorrectionRequest->attendance;

        if (!$attendance) {
            abort(404, '該当する勤怠データが存在しません。');
        }

        $date = Carbon::parse($attendance->work_date);
        $year = $date->format('Y');
        $monthDay = $date->format('m月d日');

        return view('stamp_correction_request.approve', [
            'attendanceCorrectionRequest' => $attendanceCorrectionRequest,
            'attendance' => $attendance,
            'breaks' => $attendance->breaks ?? [],
            'year' => $year,
            'monthDay' => $monthDay,
            'correctionReason' => $attendanceCorrectionRequest->reason,
        ]);
    }

    // 承認処理
    public function approve(AdminAttendanceCorrectionApproveRequest $request, AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        $attendance = $attendanceCorrectionRequest->attendance;

        // 勤怠修正申請データを更新
        $attendanceCorrectionRequest->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'admin_id' => auth()->id(),
            'note' => $request->input('note'),
        ]);

        // 関連勤怠データを更新
        if ($attendance) {
            $attendance->update([
                'work_start' => $request->input('work_start'),
                'work_end' => $request->input('work_end'),
            ]);
        }

        return redirect()->route('admin.stamp_correction_request.index')
            ->with('success', '申請を承認しました。');
    }
}
