{{-- resources/views/admin/staff/index.blade.php --}}
@extends('layouts.admin')

@section('title')
    <title>スタッフ一覧 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-staff.css') }}">
@endsection

@section('content')
    <div class="staff">
        <h1 class="staff__title"><span class="staff__title-line">|</span>スタッフ一覧</h1>

        <table class="staff__table">
            <thead>
                <tr class="staff__table-header">
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>月次勤怠</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="staff__table-row">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.staff', ['staff' => $user->id]) }}"
                                class="staff__detail-link">詳細</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">スタッフが見つかりません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection