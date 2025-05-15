<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $attendances = [
            [
                'id' => 1,
                'date' => '2025-05-01',
                'day' => '木',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_time' => '01:00',
                'total_time' => '08:00',
            ],
            [
                'id' => 2,
                'date' => '2025-05-02',
                'day' => '金',
                'start_time' => '09:15',
                'end_time' => '18:10',
                'break_time' => '01:00',
                'total_time' => '07:55',
            ],
        ];

        return view('attendance.index', compact('attendances'));
    }

    public function show($id)
    {
        // 仮データ（通常はDBから取得）
        $attendance = collect([
            1 => [
                'date' => '2025-05-01',
                'day' => '木',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_time' => '01:00',
                'total_time' => '08:00',
                'note' => '通常勤務',
            ],
            2 => [
                'date' => '2025-05-02',
                'day' => '金',
                'start_time' => '09:15',
                'end_time' => '18:10',
                'break_time' => '01:00',
                'total_time' => '07:55',
                'note' => '朝礼により出勤が遅れた',
            ],
        ])->get($id);

        $attendance = Attendance::findOrFail($id);

        // 日付に曜日を付加
        $attendanceData = [
            'id' => $attendance->id,
            'date' => $attendance->date,
            'day' => \Carbon\Carbon::parse($attendance->date)->isoFormat('ddd'),
            'start_time' => $attendance->start_time,
            'end_time' => $attendance->end_time,
            'break_time' => $attendance->break_time,
            'note' => $attendance->note,
        ];

        return view('attendance.show', ['attendance' => $attendanceData]);
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

}
