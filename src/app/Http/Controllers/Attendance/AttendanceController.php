<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Services\AttendanceService;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() && !Auth::guard('admin')->check()) {
                return redirect()->route('login'); // またはadmin用login
            }
            return $next($request);
        });
    }

    public function index(Request $request, AttendanceService $attendanceService)
    {
        $user = Auth::user();

        // クエリパラメータから「月」を取得（例: '2025-04'）
        $monthParam = $request->query('month');

        // Carbon化（'Y-m' 形式から、月初日にする）
        $currentMonth = $monthParam
            ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
            : Carbon::now()->startOfMonth();

        // 前月・翌月のCarbonオブジェクトを作成
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();
        $startDate = $currentMonth->copy()->startOfMonth()->startOfDay(); // 2025-06-01 00:00:00
        $endDate = $currentMonth->copy()->endOfMonth()->endOfDay();       // 2025-06-30 23:59:59

        // 出勤データ取得（該当月）
        $attendances = Attendance::with(['user', 'attendanceBreaks', 'breaks'])
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->map(function ($attendance) use ($attendanceService) {
                $date = $attendance->work_date ? Carbon::parse($attendance->work_date) : null;
                $start = $attendance->work_start ? Carbon::parse($attendance->work_start) : null;
                $end = $attendance->work_end ? Carbon::parse($attendance->work_end) : null;

                $totalBreak = $attendanceService->calculateBreakTime($attendance);
                $totalTime = $attendanceService->calculateWorkingTime($attendance);

                return [
                    'id' => $attendance->id,
                    'work_date' => $date ? $date->format('Y-m-d') : '',
                    'day' => $date ? $date->isoFormat('dd') : '',
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

    public function show($id, AttendanceService $attendanceService)
    {
        $attendance = Attendance::with('breaks')->findOrFail($id);
        $workingMinutes = $attendanceService->calculateWorkingTime($attendance);
        $layout = auth('admin')->check() ? 'layouts.admin' : 'layouts.app';

        // 修正申請（承認待ち）を取得
        $correctionRequest = AttendanceCorrectionRequest::where('attendance_id', $attendance->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // 備考（修正申請があればそれを使用）
        $correctionReason = optional($correctionRequest)->reason ?? $attendance->correction_reason ?? '';

        // 表示日付
        $dateSource = $attendance->work_date ?? $attendance->work_start ?? now();
        $carbonDate = \Carbon\Carbon::parse($dateSource);

        $year = $carbonDate->format('Y年');
        $monthDay = $carbonDate->format('m月d日');

        return view('attendance.show', [
            'attendance' => $attendance,
            'layout' => $layout,
            'workingMinutes' => $workingMinutes,
            'correctionRequest' => $correctionRequest,
            'correctionReason' => $correctionReason,
            'year' => $year,
            'monthDay' => $monthDay,
        ]);
    }


    public function showStaffAttendance($userId, Request $request, AttendanceService $attendanceService)
    {
        $user = User::findOrFail($userId);

        $month = $request->query('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $attendances = Attendance::with('breaks')
            ->where('user_id', $userId)
            ->whereMonth('work_start', $currentMonth->month)
            ->whereYear('work_start', $currentMonth->year)
            ->get()
            ->map(function ($attendance) use ($attendanceService) {
                $start = $attendance->work_start ? Carbon::parse($attendance->work_start) : null;
                $end = $attendance->work_end ? Carbon::parse($attendance->work_end) : null;

                $totalBreak = $attendanceService->calculateBreakTime($attendance);
                $totalTime = $attendanceService->calculateWorkingTime($attendance);

                return [
                    'id' => $attendance->id,
                    'work_date' => $start ? $start->format('Y-m-d') : '',
                    'day' => $start ? $start->isoFormat('dd') : '',
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
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->latest()
            ->first();
        $status = 'none';

        $lastRest = null;
        if ($attendance) {
            $lastRest = $attendance->attendanceBreaks()->latest()->first();
        }

        if (!$attendance) {
            $status = 'none';
        } elseif ($attendance->work_end) {
            $status = 'finished';
        } elseif ($attendance->attendanceBreaks()->whereNull('rest_end_time')->exists()) {
            $status = 'resting';
        } elseif ($attendance->work_start) {
            $status = 'working';
        } else {
            $status = 'none';
        }

        $weekday = $today->isoFormat('dddd');

        return view('attendance.create', [
            'lastRest' => $lastRest,
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
            'work_date' => Carbon::today(),
        ]);

        return redirect()->route('attendance.create');
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
                'rest_start_time' => Carbon::now(),
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
                ->whereNull('rest_end_time')
                ->latest()
                ->first();

            if ($restLog) {
                $restLog->update([
                    'rest_end_time' => Carbon::now(),
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

    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        $request->validate([
            'work_start' => 'nullable|date_format:H:i',
            'work_end' => 'nullable|date_format:H:i|after:work_start',
            'reason' => 'required|string|max:255',
            'break_start_times.*' => 'nullable|date_format:H:i',
            'break_end_times.*' => 'nullable|date_format:H:i|after_or_equal:break_start_times.*',
        ]);

        DB::beginTransaction();

        try {
            // 勤怠情報にステータスだけ反映
            $attendance->status = 'pending';
            $attendance->is_edited = true;
            $attendance->requested_at = now();
            $attendance->save();

            // 修正申請として保存（承認待ち一覧に出すため）
            AttendanceCorrectionRequest::create([
                'attendance_id' => $attendance->id,
                'user_id' => auth()->id(),
                'work_start' => $request->input('work_start'),
                'work_end' => $request->input('work_end'),
                'break_start_times' => json_encode($request->input('break_start_times', [])),
                'break_end_times' => json_encode($request->input('break_end_times', [])),
                'reason' => $request->input('reason'),
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('attendance.show', ['attendance' => $attendance->id])
                ->with('status', '修正申請を提出しました');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '申請の保存に失敗しました。もう一度お試しください。']);
        }
    }
}