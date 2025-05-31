<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Services\AttendanceService;

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

        // 出勤データ取得（該当月）
        $attendances = Attendance::with(['user', 'attendanceBreaks', 'breaks'])
            ->where('user_id', $user->id)
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
                    'work_start' => $start ? $start->format('H:i') : '—',
                    'work_end' => $end ? $end->format('H:i') : '—',
                    'break_time' => gmdate('H:i', $totalBreak * 60),
                    'total_time' => $totalTime !== null ? gmdate('H:i', $totalTime * 60) : '—',
                ];
            });

        // 表示確認のため一時的にログ出力（開発中のみ）
        \Log::debug('monthParam: ' . $monthParam);
        \Log::debug('currentMonth: ' . $currentMonth->format('Y-m'));

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

        // 申請理由取得
        $reasonFromUrl = request()->query('reason');
        $correctionRequest = \App\Models\AttendanceCorrectionRequest::where('user_id', $attendance->user_id)
            ->whereDate('target_date', $attendance->work_date)
            ->latest()
            ->first();
        $correctionReason = $reasonFromUrl ?? optional($correctionRequest)->reason;

        $targetDate = request()->query('target_date');
        if ($targetDate) {
            $carbonDate = \Carbon\Carbon::parse($targetDate);
        } else {
            $carbonDate = \Carbon\Carbon::parse($attendance->work_start);
        }

        $year = $carbonDate->format('Y年');
        $monthDay = $carbonDate->format('m月d日');

        return view('attendance.show', [
            'attendance' => $attendance,
            'layout' => $layout,
            'workingMinutes' => $workingMinutes,
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
        // 勤怠情報の更新処理
        $attendance->update([
            'work_start' => $request->input('work_start'),
            'work_end' => $request->input('work_end'),
            'note' => $request->input('note'),
            'is_edited' => true, // 修正済みフラグを立てる
        ]);

        // セッションにフラッシュメッセージを保存
        return redirect()->route('attendance.show', ['attendance' => $attendance->id])
            ->with('status', 'edited');
    }
}