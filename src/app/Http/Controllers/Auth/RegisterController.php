<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // これを追加：登録イベントを発火（→ 認証メールが送信される）
        event(new Registered($user));

        // ログイン処理（必要であれば）
        auth()->login($user);

        return redirect()->route('verification.notice');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
