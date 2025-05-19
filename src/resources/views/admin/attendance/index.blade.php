@extends('layouts.admin')

@section('title')
    <title>勤怠一覧 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-attendance.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <h1 class="attendance__title">{{ $date->format('Y年n月j日') }}の勤怠</h1>

        <div class="attendance__navigation">
            <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                class="attendance__nav--prev">← 前日</a>

            <div class="attendance__nav--current">
                <img src="{{ asset('images/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
                <span>{{ $date->format('Y年n月j日') }}</span>
            </div>

            <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                class="attendance__nav--next">翌日 →</a>
        </div>

        <table class="attendance__table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->clock_in_time ? $attendance->clock_in_time->format('H:i') : '—' }}</td>
                        <td>{{ $attendance->clock_out_time ? $attendance->clock_out_time->format('H:i') : '—' }}</td>
                        <td>{{ $attendance->break_duration ?? '—' }}</td>
                        <td>{{ $attendance->total_work_time ?? '—' }}</td>
                        <td>
                            <a href="{{ url('/attendance/' . $attendance['id']) }}" class="attendance__detail-link">詳細</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">データがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection