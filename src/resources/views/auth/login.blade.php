{{-- resources/views/auth/login.blade.php --}}
@extends('layouts/app')

@section('title')
    <title>ログイン</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth-login.css') }}">
@endsection

@section('content')
    <div class="content">
        <div class="login-title">
            <p>ログイン</p>
        </div>

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            @if ($errors->has('login_error'))
                <div class="text-danger">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-button">
                <button type="submit" class="btn btn-primary">ログインする</button>
                <p class="create-account"><a href="{{ route('register') }}">会員登録はこちら</a></p>
            </div>

        </form>
    </div>
@endsection