<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AdminStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $users = User::all(); // 一般ユーザーをすべて取得
        return view('admin.staff.index', compact('users'));
    }

    public function attendance(User $staff, Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_date')
            ->get();

        $attendanceService = app(\App\Services\AttendanceService::class);

        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $attendances = Attendance::with(['user', 'attendanceBreaks'])
            ->where('user_id', $staff->id)
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_date')
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

        return view('admin.attendance.staff', [
            'date' => $date,
            'staff' => $staff,
            'attendances' => $attendances,
            'currentMonth' => $startOfMonth,
            'prevMonth' => $startOfMonth->copy()->subMonth(),
            'nextMonth' => $startOfMonth->copy()->addMonth(),
        ]);
    }
}