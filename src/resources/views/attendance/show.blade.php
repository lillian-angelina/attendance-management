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

        <form action="{{ route('attendance.request', ['id' => $attendance['id']]) }}" method="POST" class="attendance-form">
            @csrf
            <table border="1" cellspacing="0" cellpadding="8" style="margin-top: 20px;">
                <tr>
                    <th>日付</th>
                    <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('Y年m月d日') }}（{{ $attendance['day'] }}）</td>
                </tr>
                <tr>
                    <th>出勤時間</th>
                    <td>
                        <input type="time" name="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($attendance['start_time'])->format('H:i')) }}">
                    </td>
                </tr>
                <tr>
                    <th>退勤時間</th>
                    <td>
                        <input type="time" name="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($attendance['end_time'])->format('H:i')) }}">
                    </td>
                </tr>
                <tr>
                    <th>休憩時間</th>
                    <td>
                        <input type="text" name="break_time" placeholder="例: 01:00"
                            value="{{ old('break_time', $attendance['break_time']) }}">
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="note" rows="3" cols="40">{{ old('note', $attendance['note']) }}</textarea>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20px;">
                <button type="submit">修正申請する</button>
            </div>
        </form>

        <div style="margin-top: 20px;">
            <a href="{{ url('/attendance/list') }}">← 勤怠一覧へ戻る</a>
        </div>
    </div>
@endsection