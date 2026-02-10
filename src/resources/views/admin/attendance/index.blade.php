{{-- resources/views/admin/attendance/index.blade.php --}}
@extends('layouts.admin')

@section('title')
    <title>勤怠一覧 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-attendance.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <h1 class="attendance__title">
            <span class="attendance__title-line">|</span>{{ $date->format('Y年n月j日') }}の勤怠
        </h1>

        <div class="attendance__navigation">
            <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                class="attendance__nav attendance__nav--prev">
                <img src="{{ asset('images/←.png') }}" alt="前日" class="icon">前日
            </a>

            <div class="attendance__nav attendance__nav--current" style="position: relative; cursor: pointer;"
                onclick="document.getElementById('datePicker').showPicker()">
                <img src="{{ asset('images/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
                <span>{{ $date->format('Y年n月j日') }}</span>

                <input type="date" id="datePicker" value="{{ $date->format('Y-m-d') }}"
                    style="position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;"
                    onchange="handleDateChange(this.value)">
            </div>

            <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                class="attendance__nav attendance__nav--next">
                翌日<img src="{{ asset('images/→.png') }}" alt="翌日" class="icon">
            </a>
        </div>

        <table class="attendance__table">
            <thead>
                <tr class="attendance__table-header">
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
                    <tr class="attendance__table-row">
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->formatted_work_start ?? '—' }}</td>
                        <td>{{ $attendance->formatted_work_end ?? '—' }}</td>
                        <td>{{ $attendance->formatted_break_time ?? '—' }}</td>
                        <td>{{ $attendance->formatted_total_time ?? '—' }}</td>
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
    <script>
        function handleDateChange(selectedDate) {
            const baseUrl = "{{ route('admin.attendance.list') }}";
            window.location.href = baseUrl + "?date=" + selectedDate;
        }
    </script>
@endsection