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
    <h1 class="staff__title">スタッフ一覧</h1>

    <table class="staff__table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffs as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td>
                        <a href="{{ route('admin.staff.attendance', ['staff' => $staff->id]) }}" class="staff__detail-link">詳細</a>
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
