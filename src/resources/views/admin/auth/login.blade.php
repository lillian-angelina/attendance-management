{{-- resources/views/admin/auth/login.blade.php --}}
@extends('layouts/admin')

@section('title')
    <title>管理者ログイン</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-auth-login.css') }}">
@endsection

@section('content')
    <div class="admin-login">
        <h1 class="admin-login__title">管理者ログイン</h1>

        <form method="POST" action="{{ route('admin.auth.login') }}" class="admin-login__form">
            @csrf

            <div class="admin-login__field">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="admin-login__error">{{ $message }}</div>
                @enderror
            </div>

            @if ($errors->has('login_error'))
                <div class="admin-login__error">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            <div class="admin-login__field">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password">
                @error('password')
                    <div class="admin-login__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-login__actions">
                <button type="submit" class="admin-login__btn">管理者ログインする</button>
            </div>
        </form>
    </div>
@endsection