<?php

namespace App\Http\Controllers;

use App\Models\AttendanceBreak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;

class StampCorrectionRequestController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = auth('admin')->check();
        $user = $isAdmin ? auth('admin')->user() : auth()->user();
        $layout = $isAdmin ? 'layouts.admin' : 'layouts.app';

        $status = $request->input('status', 'pending');

        $query = AttendanceCorrectionRequest::with(['attendance.user'])
            ->where('status', $status)
            ->orderBy('requested_at', 'desc');

        if (!$isAdmin) {
            $query->whereHas('attendance', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $requests = $query->get();

        return view('stamp_correction_request.index', [
            'layout' => $layout,
            'layoutView' => $layout,
            'requests' => $requests,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function store(Request $request)
    {
        $correctionRequest = Attendance::create([
            'user_id' => Auth::id(),
            'date' => $request->input('date'),
            'work_start' => $request->input('work_start'),
            'work_end' => $request->input('work_end'),
            'status' => 'pending',
            'requested_at' => Carbon::now(),
        ]);

        $correctionRequest = AttendanceCorrectionRequest::create([
            'user_id' => Auth::id(),
            'attendance_id' => $correctionRequest->id,
            'date' => $request->input('date'),
            'work_start' => $request->input('work_start'),
            'work_end' => $request->input('work_end'),
            'reason' => $request->input('reason'),
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

    public function update(Request $request, AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        $attendanceCorrectionRequest->work_start = $request->input('work_start');
        $attendanceCorrectionRequest->work_end = $request->input('work_end');
        $attendanceCorrectionRequest->status = 'pending';
        $attendanceCorrectionRequest->requested_at = now();
        $attendanceCorrectionRequest->reason = $request->input('reason');

        $attendanceCorrectionRequest->update([
            'status' => $request->input('status'),
            'reason' => $request->input('reason'),
            'admin_id' => Auth::id(),
        ]);

        $attendanceCorrectionRequest->save();

        return redirect()->route('stamp_correction_request.index')->with('success', '修正申請を更新しました。');
    }
}
