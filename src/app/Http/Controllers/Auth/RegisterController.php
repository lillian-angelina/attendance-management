<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // resources/views/auth/register.blade.php
    }

    public function register(RegisterRequest $request)
    {
        // バリデーション済みデータの取得
        $validated = $request->validated();

        // ユーザー登録処理
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // ログイン後のリダイレクト等
        return redirect()->route('dashboard')->with('success', '登録が完了しました。');
    }
}
