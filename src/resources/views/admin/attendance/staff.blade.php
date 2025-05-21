@extends('layouts.admin')

@section('title')
    <title>スタッフ別勤怠一覧 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-attendance-staff.css') }}">
@endsection

@section('content')
    <div class="attendance-staff">
        <h1 class="attendance-staff__title">{{ $staff->name }} さんの勤怠一覧</h1>

        {{-- 月切り替えナビゲーション --}}
        <div class="attendance-staff__nav">
            <a href="{{ route('admin.attendance.staff', ['staff' => $staff->id, 'month' => $prevMonth->format('Y-m')]) }}"
                class="attendance-staff__nav-link">←前月</a>

            <div class="attendance-staff__month">
                <i class="fas fa-calendar-alt"><img src="{{ asset('images/calendar-icon.png') }}"></i>
                {{ $currentMonth->format('Y年m月') }}
            </div>

            <a href="{{ route('admin.attendance.staff', ['staff' => $staff->id, 'month' => $nextMonth->format('Y-m')]) }}"
                class="attendance-staff__nav-link">翌月→</a>
        </div>

        {{-- 勤怠テーブル --}}
        <table class="attendance-staff__table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->work_date)->format('n/j(D)') }}</td>
                        <td>{{ $attendance->work_start ? \Carbon\Carbon::parse($attendance->work_start)->format('H:i') : '—' }}
                        </td>
                        <td>{{ $attendance->work_end ? \Carbon\Carbon::parse($attendance->work_end)->format('H:i') : '—' }}</td>
                        <td>{{ $attendance->break_time > 0 ? gmdate('H:i', $attendance->break_time * 60) : '-' }}</td>
                        <td>{{ $attendance->total_time > 0 ? gmdate('H:i', $attendance->total_time * 60) : '-' }}</td>
                        <td>
                            <a href="{{ url('/attendance/' . $attendance->id) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection