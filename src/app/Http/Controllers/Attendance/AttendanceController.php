<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $month = $request->query('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $attendances = Attendance::with('attendanceBreaks')
            ->where('user_id', $user->id)
            ->whereMonth('work_start', $currentMonth->month)
            ->whereYear('work_start', $currentMonth->year)
            ->get()
            ->map(function ($attendance) {
                $start = $attendance->work_start ? Carbon::parse($attendance->work_start) : null;
                $end = $attendance->work_end ? Carbon::parse($attendance->work_end) : null;

                // 休憩時間合計（分）
                $totalBreak = $attendance->attendanceBreaks->reduce(function ($carry, $break) {
                    $start = $break->rest_start_time ? Carbon::parse($break->rest_start_time) : null;
                    $end = $break->rest_end_time ? Carbon::parse($break->rest_end_time) : null;
                    if ($start && $end) {
                        return $carry + $end->diffInMinutes($start);
                    }
                    return $carry;
                }, 0);

                // 勤務時間（分）
                $totalTime = ($start && $end) ? $start->diffInMinutes($end) - $totalBreak : null;

                return [
                    'id' => $attendance->id,
                    'work_date' => $attendance->work_start ? Carbon::parse($attendance->work_start)->format('Y-m-d') : '',
                    'day' => $attendance->work_start ? Carbon::parse($attendance->work_start)->isoFormat('dd') : '',
                    'work_start' => $start ? $start->format('H:i') : '—',
                    'work_end' => $end ? $end->format('H:i') : '—',
                    'break_time' => gmdate('H:i', $totalBreak * 60),
                    'total_time' => $totalTime !== null ? gmdate('H:i', $totalTime * 60) : '—',
                ];
            });

        return view('attendance.index', compact(
            'user',
            'attendances',
            'currentMonth',
            'prevMonth',
            'nextMonth'
        ));
    }

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);

        // ガードで判定
        $layout = auth('admin')->check() ? 'layouts.admin' : 'layouts.app';

        return view('attendance.show', [
            'attendance' => $attendance,
            'layout' => $layout,
        ]);
    }


    public function showStaffAttendance($userId, Request $request)
    {
        $user = User::findOrFail($userId);

        $month = $request->query('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $attendances = Attendance::with('attendanceBreaks') // リレーション取得
            ->where('user_id', $userId)
            ->whereMonth('work_start', $currentMonth->month)
            ->whereYear('work_start', $currentMonth->year)
            ->get()
            ->map(function ($attendance) {
                // 勤務開始・終了
                $start = $attendance->work_start ? Carbon::parse($attendance->work_start) : null;
                $end = $attendance->work_end ? Carbon::parse($attendance->work_end) : null;

                // 休憩合計時間（分）
                $totalBreak = $attendance->attendanceBreaks->reduce(function ($carry, $break) {
                    $start = $break->rest_start_time ? Carbon::parse($break->rest_start_time) : null;
                    $end = $break->rest_end_time ? Carbon::parse($break->rest_end_time) : null;
                    if ($start && $end) {
                        return $carry + $end->diffInMinutes($start);
                    }
                    return $carry;
                }, 0);

                // 合計勤務時間（出勤〜退勤 − 休憩）
                $totalTime = ($start && $end) ? $end->diffInMinutes($start) - $totalBreak : null;

                return [
                    'id' => $attendance->id,
                    'work_date' => $attendance->work_start ? Carbon::parse($attendance->work_start)->format('Y-m-d') : '',
                    'day' => $attendance->work_start ? Carbon::parse($attendance->work_start)->isoFormat('dd') : '',
                    'start_time' => $start ? $start->format('H:i') : '—',
                    'end_time' => $end ? $end->format('H:i') : '—',
                    'break_time' => gmdate('H:i', $totalBreak * 60),
                    'total_time' => $totalTime !== null ? gmdate('H:i', $totalTime * 60) : '—',
                ];
            });

        return view('attendance.staff', compact(
            'user',
            'attendances',
            'currentMonth',
            'prevMonth',
            'nextMonth'
        ));
    }


    public function create(Request $request)
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        $status = 'none';

        $lastRest = null;
        if ($attendance) {
            $lastRest = $attendance->attendanceBreaks()->latest()->first();
        }

        if ($attendance && $attendance->work_end) {
            $status = 'finished';
        } elseif ($lastRest && is_null($lastRest->rest_end)) {
            $status = 'resting';
        } elseif ($attendance && $attendance->work_start) {
            $status = 'working';
        }

        $today = Carbon::today();
        $weekday = $today->isoFormat('dddd');

        return view('attendance.create', [
            'status' => $status,
            'today' => $today->format('Y年m月d日'),
            'weekday' => $weekday,
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

    // 休憩開始
    public function startRest()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        if ($attendance) {
            $attendance->attendanceBreaks()->create([
                'rest_start' => Carbon::now(),
            ]);
        }

        return redirect()->back();
    }

    // 休憩終了
    public function endRest()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_start', Carbon::today())
            ->latest()
            ->first();

        if ($attendance) {
            $restLog = $attendance->attendanceBreaks()
                ->whereNull('rest_end')
                ->latest()
                ->first();

            if ($restLog) {
                $restLog->update([
                    'rest_end' => Carbon::now(),
                ]);
            }
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

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'work_start' => 'required|date_format:H:i',
            'work_end' => 'required|date_format:H:i|after:work_start',
            'break_time' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        // 日付を保持したまま時間だけ更新
        $date = \Carbon\Carbon::parse($attendance->work_start)->format('Y-m-d');
        $attendance->work_start = $date . ' ' . $request->work_start . ':00';
        $attendance->work_end = $date . ' ' . $request->work_end . ':00';
        $attendance->break_time = $request->break_time;
        $attendance->note = $request->note;
        $attendance->is_edited = true; // 修正申請フラグ

        $attendance->save();

        return redirect()->back()->with('success', '修正申請が送信されました。');
    }
}
