<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Http\Requests\AdminAttendanceCorrectionApproveRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AdminStampCorrectionRequestController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $isAdmin = auth('admin')->check();
        $user = $isAdmin ? auth('admin')->user() : auth()->user();

        $layout = $isAdmin ? 'layouts.admin' : 'layouts.app';

        $status = $request->input('status', 'pending');

        $query = Attendance::with('user')->where('status', $status);

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $requests = $query->get();

        return view('stamp_correction_request.index', [
            'layout' => $layout,
            'layoutView' => $layout,
            'requests' => $requests,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function showApprove(AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        $attendanceCorrectionRequest = AttendanceCorrectionRequest::with(['attendance.breaks', 'user', 'attendance.user'])
            ->findOrFail($attendanceCorrectionRequest->id);

        $requests = AttendanceCorrectionRequest::with('attendance')->get();

        $attendance = $attendanceCorrectionRequest->attendance;

        if (!$attendance) {
            abort(404, '該当する勤怠データが存在しません。');
        }

        $date = $attendance->work_date ? Carbon::parse($attendance->work_date) : null;
        $year = $date ? $date->format('Y') : '';
        $monthDay = $date ? $date->format('m月d日') : '';

        return view('stamp_correction_request.approve', [
            'attendanceCorrectionRequest' => $attendanceCorrectionRequest,
            'attendance' => $attendance,
            'breaks' => $attendance->breaks ?? [],
            'requests' => $requests,
            'year' => $year,
            'monthDay' => $monthDay,
            'correctionReason' => $attendanceCorrectionRequest->reason,
        ]);
    }

    // 承認処理
    public function approve(AdminAttendanceCorrectionApproveRequest $request, AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        $user = auth('admin')->user();

        if (!$user || !method_exists($user, 'isAdmin') || !$user->isAdmin()) {
            abort(403, '承認権限がありません。');
        }

        $attendance = $attendanceCorrectionRequest->attendance;

        $attendanceCorrectionRequest->update([
            'status' => 'approved',
            'is_edited' => true,
            'reviewed_at' => now(),
            'admin_id' => auth()->id(),
            'reason' => $request->input('reason'),
        ]);

        if ($attendance) {
            $today = now()->toDateString();
            $attendance->update([
                'work_start' => $today . ' ' . $request->input('work_start'),
                'work_end' => $today . ' ' . $request->input('work_end'),
                'status' => 'approved',
                'is_edited' => true,
            ]);
        }

        return redirect()->route('stamp_correction_request.approve', $attendanceCorrectionRequest->id)
            ->with('status', 'edited');
    }
}
