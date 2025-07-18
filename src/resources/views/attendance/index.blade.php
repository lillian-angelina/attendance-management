{{-- resources/views/attendance/index.blade.php --}}
@extends('layouts.app')

@section('title')
    <title>勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-list-index.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <h1 class="attendance__title"><span class="attendance__title-line">|</span>勤怠一覧</h1>

        <div class="attendance__navigation">
            <a href="{{ route('attendance.list', ['month' => $prevMonth->format('Y-m')]) }}" class="attendance__nav--prev">
                <img src="{{ asset('images/←.png') }}" alt="#" class="icon">前月
            </a>

        <div class="attendance__nav--current">
            <img src="{{ asset('images/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
            <span>{{ $currentMonth->format('Y年n月') }}</span>
        </div>

        <a href="{{ route('attendance.list', ['month' => $nextMonth->format('Y-m')]) }}" class="attendance__nav--next">
            翌月<img src="{{ asset('images/→.png') }}" alt="#" class="icon">
        </a>
    </div>

    <table class="attendance__table">
        <thead>
            <tr class="attendance__table-header">
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
                <tr class="attendance__table-row">
                    <td>
                        @if ($attendance['work_date'])
                            {{ \Carbon\Carbon::parse($attendance['work_date'])->format('m/d') }} ({{ $attendance['day'] }})
                        @else
                            未設定
                        @endif
                    </td>
                    <td>{{ $attendance['work_start'] }}</td>
                    <td>{{ $attendance['work_end'] }}</td>
                    <td>{{ $attendance['break_time'] }}</td>
                    <td>{{ $attendance['total_time'] }}</td>
                    <td>
                        <a href="{{ route('attendance.show', ['attendance' => $attendance['id']]) }}"
                            class="attendance__detail-link">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
@endsection