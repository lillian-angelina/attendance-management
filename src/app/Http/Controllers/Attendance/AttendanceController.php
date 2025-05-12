<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        $status = '未出勤';

        if ($attendance) {
            if ($attendance->work_end) {
                $status = '退勤済';
            } elseif ($attendance->rest_start && !$attendance->rest_end) {
                $status = '休憩中';
            } elseif ($attendance->work_start) {
                $status = '出勤中';
            }
        }

        return view('attendance.create', [
            'status' => $status,
            'today' => Carbon::today()->format('Y年m月d日'),
            'weekday' => Carbon::today()->formatLocalized('%A'),
            'time' => Carbon::now()->format('H:i:s'),
        ]);
    }

    public function startWork()
    {
        Attendance::create([
            'user_id' => Auth::id(),
            'work_start' => Carbon::now(),
        ]);

        return redirect()->back();
    }

    public function startRest()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        if ($attendance && !$attendance->rest_start) {
            $attendance->update(['rest_start' => Carbon::now()]);
        }

        return redirect()->back();
    }

    public function endRest()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        if ($attendance && $attendance->rest_start && !$attendance->rest_end) {
            $attendance->update(['rest_end' => Carbon::now()]);
        }

        return redirect()->back();
    }

    public function endWork()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        if ($attendance && !$attendance->work_end) {
            $attendance->update(['work_end' => Carbon::now()]);
        }

        return redirect()->back();
    }
}
