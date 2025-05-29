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
        $attendanceCorrectionRequest = AttendanceCorrectionRequest::with('attendance.breaks')->findOrFail($attendanceCorrectionRequest->id);

        return view('stamp_correction_request.approve', [
            'attendanceCorrectionRequest' => $attendanceCorrectionRequest,
            'breaks' => $attendanceCorrectionRequest->breaks
        ]);
    }

    // 承認処理
    public function approve(AdminAttendanceCorrectionApproveRequest $request, AttendanceCorrectionRequest $attendanceRequest)
    {
        $attendanceRequest->update([
            'status' => 'approved',
            'reviewed_at' => $request->input('reviewed_at', now()),
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.stamp_correction_request.index')->with('success', '申請を承認しました。');
    }
}
