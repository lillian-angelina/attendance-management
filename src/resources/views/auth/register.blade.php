{{-- resources/views/auth/register.blade.php --}}
@extends('layouts/app')

@section('title')
    <title>会員登録</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth-register.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="register-title">
            <p>会員登録</p>
        </div>
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-button">
                <button type="submit" class="btn btn-primary">登録する</button>
                <p class="create-account"><a href="{{ route('login') }}">ログインはこちら</a></p>
            </div>
        </form>
    </div>
@endsection