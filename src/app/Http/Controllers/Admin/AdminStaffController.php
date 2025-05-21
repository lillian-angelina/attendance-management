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

    public function attendance(User $staff, Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_date')
            ->get();

        return view('admin.attendance.staff', [
            'staff' => $staff,
            'attendances' => $attendances,
            'currentMonth' => $startOfMonth,
            'prevMonth' => $startOfMonth->copy()->subMonth(),
            'nextMonth' => $startOfMonth->copy()->addMonth(),
        ]);
    }
}