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

    <table border="1" cellspacing="0" cellpadding="8" style="margin-top: 20px;">
        <tr>
            <th>日付</th>
            <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('Y年m月d日') }}（{{ $attendance['day'] }}）</td>
        </tr>
        <tr>
            <th>出勤時間</th>
            <td>{{ $attendance['start_time'] }}</td>
        </tr>
        <tr>
            <th>退勤時間</th>
            <td>{{ $attendance['end_time'] }}</td>
        </tr>
        <tr>
            <th>休憩時間</th>
            <td>{{ $attendance['break_time'] }}</td>
        </tr>
        <tr>
            <th>合計勤務時間</th>
            <td>{{ $attendance['total_time'] }}</td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $attendance['note'] ?? 'なし' }}</td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <a href="{{ url('/attendance/list') }}">← 勤怠一覧へ戻る</a>
    </div>
</div>
@endsection
