<?php

// StampCorrectionRequestController.php

namespace App\Http\Controllers;

use App\Models\AttendanceBreak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Models\Attendance;



class StampCorrectionRequestController extends Controller
{
    public function index(Request $request)
    {
        // 管理者認証と一般ユーザー認証のどちらでログインしているかを確認
        $isAdmin = auth('admin')->check();
        $user = $isAdmin ? auth('admin')->user() : auth()->user();

        // レイアウト切り替え
        $layout = $isAdmin ? 'layouts.admin' : 'layouts.app';

        // ステータス絞り込み（例: pending / approved）
        $status = $request->input('status', 'pending');

        // クエリビルダー
        $query = Attendance::with('user')->where('status', $status);

        // 管理者でなければ、自分のデータのみに絞る
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

    // ★ 申請保存処理を追加
    public function store(Request $request)
    {
        $correctionRequest = Attendance::create([
            'user_id' => Auth::id(),
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
}
