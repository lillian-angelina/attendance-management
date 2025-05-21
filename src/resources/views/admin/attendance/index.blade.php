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
                        <td>{{ $attendance->work_start ? \Carbon\Carbon::parse($attendance->work_start)->format('H:i') : '—' }}
                        </td>
                        <td>{{ $attendance->work_end ? \Carbon\Carbon::parse($attendance->work_end)->format('H:i') : '—' }}</td>
                        <td>{{ $attendance->break_time ? \Carbon\Carbon::parse($attendance->break_time)->format('H:i') : '—' }}
                        </td>
                        @php
                            $start = $attendance->work_start ? \Carbon\Carbon::parse($attendance->work_start) : null;
                            $end = $attendance->work_end ? \Carbon\Carbon::parse($attendance->work_end) : null;
                            $break = $attendance->break_time ? \Carbon\Carbon::parse($attendance->break_time)->diffInMinutes(Carbon\Carbon::createFromTime(0, 0)) : 0;

                            $total = ($start && $end) ? $end->diffInMinutes($start) - $break : null;
                        @endphp

                        <td>{{ $total !== null ? gmdate('H:i', $total * 60) : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.staff', ['staff' => $attendance->user_id]) }}"
                                class="attendance__detail-link">詳細</a>
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