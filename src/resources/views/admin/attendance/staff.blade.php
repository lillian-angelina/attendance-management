@extends('layouts.admin')

@section('title')
    <title>スタッフ別勤怠一覧 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-attendance-staff.css') }}">
@endsection

@section('content')
    <div class="attendance-staff">
        <h1 class="attendance-staff__title"><span class="attendance__staff-line">|</span>{{ $staff->name }} さんの勤怠一覧</h1>

        {{-- 月切り替えナビゲーション --}}
        <div class="attendance-staff__nav">
            <a href="{{ route('admin.attendance.staff', ['staff' => $staff->id, 'month' => $prevMonth->format('Y-m')]) }}"
                class="attendance-staff__nav-link"><img src="{{ asset('images/←.png') }}" alt="#" class="icon">前月</a>

            <div class="attendance-staff__month">
                <i class="fas fa-calendar-alt"><img src="{{ asset('images/calendar-icon.png') }}" class="calendar-icon"></i>
                {{ $currentMonth->format('Y年m月') }}
            </div>

            <a href="{{ route('admin.attendance.staff', ['staff' => $staff->id, 'month' => $nextMonth->format('Y-m')]) }}"
                class="attendance-staff__nav-link">翌月<img src="{{ asset('images/→.png') }}" alt="#" class="icon"></a>
        </div>

        {{-- 勤怠テーブル --}}
        <table class="attendance-staff__table">
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
                        <td>{{ \Carbon\Carbon::parse($attendance->work_date)->locale('ja')->translatedFormat('n/j(D)') }}</td>
                        <td>{{ $attendance->formatted_work_start ?? '-' }}</td>
                        <td>{{ $attendance->formatted_work_end ?? '-' }}</td>
                        <td>{{ $attendance->formatted_break_time ?? '-' }}</td>
                        <td>{{ $attendance->formatted_total_time ?? '-' }}</td>
                        <td>
                            <a href="{{ route('attendance.show', ['attendance' => $attendance['id']]) }}"
                                class="attendance__detail-link">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection