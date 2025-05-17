<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $attendances = Attendance::with('staff')
            ->whereDate('work_date', $date)
            ->get();

        return view('admin.attendance.index', compact('date', 'attendances'));
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/attendance/list');
        }

        return back()->with('error', 'メールアドレスまたはパスワードが正しくありません');
    }
    
}
