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
    public function index()
    {
        $users = User::all(); // 一般ユーザーをすべて取得

        return view('admin.staff.index', compact('users'));
    }

    public function attendance($id, Request $request)
    {
        $admin = User::findOrFail($id);

        $month = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $id)
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_date')
            ->get();

        return view('admin.attendance.staff', [
            'admin' => $admin = Auth::guard('admin')->user(),
            'attendances' => $attendances,
            'currentMonth' => $month,
            'prevMonth' => $month->copy()->subMonth(),
            'nextMonth' => $month->copy()->addMonth(),
        ]);
    }
}
