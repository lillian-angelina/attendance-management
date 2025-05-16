@extends('layouts.app')

@section('title')
    <title>勤怠詳細</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <h2>勤怠詳細</h2>
        <p>日付：{{ \Carbon\Carbon::parse($attendance->work_start)->format('Y/m/d') }}
            ({{ \Carbon\Carbon::parse($attendance->work_start)->isoFormat('dddd') }})</p>
        <p>出勤時間：{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}</p>
        <p>退勤時間：{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}</p>
        <p>休憩時間：{{ $attendance->break_time ?? '01:00' }}</p>
        <p>備考：{{ $attendance['note'] }}</p>

        @if (!$attendance['is_edited'])
            <a href="{{ url('/attendance/' . $attendance['id'] . '/edit') }}" class="btn btn-primary">修正</a>
        @else
            <p style="color: red;">※承認待ちのため修正はできません</p>
        @endif
    </div>
@endsection