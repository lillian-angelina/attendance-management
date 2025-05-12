<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-email'); // resources/views/auth/verify-email.blade.php
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/attendance'); // 認証済の場合
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '確認メールを再送しました。');
    }
}
