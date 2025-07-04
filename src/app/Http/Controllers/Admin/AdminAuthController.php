<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Http\Requests\LoginRequest;

class AdminAuthController extends Controller
{
    public function index(Request $request)
    {
        $attendanceService = app(\App\Services\AttendanceService::class);

        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $attendances = Attendance::with(['user', 'attendanceBreaks'])
            ->whereDate('work_date', $date)
            ->get()
            ->map(function ($attendance) use ($attendanceService) {
                $start = $attendance->work_start ? Carbon::parse($attendance->work_start) : null;
                $end = $attendance->work_end ? Carbon::parse($attendance->work_end) : null;

                $totalBreakMinutes = $attendanceService->calculateBreakTime($attendance);
                $totalWorkMinutes = ($start && $end) ? $attendanceService->calculateWorkingTime($attendance) : null;

                $attendance->formatted_work_start = $start ? $start->format('H:i') : null;
                $attendance->formatted_work_end = $end ? $end->format('H:i') : null;
                $attendance->formatted_break_time = $totalBreakMinutes ? gmdate('H:i', $totalBreakMinutes * 60) : null;
                $attendance->formatted_total_time = $totalWorkMinutes !== null ? gmdate('H:i', $totalWorkMinutes * 60) : null;

                return $attendance;
            });

        return view('admin.attendance.index', compact('date', 'attendances'));
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/attendance/list');
        }
        return back()->withErrors([
            'login_error' => 'ログイン情報が登録されていません。',
        ])->withInput();
    }
}
