@extends('layouts.app')

@section('title')
    <title>勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-list-index.css') }}">
@endsection

@section('content')
    <div class="attendance-list">
        <h2>勤怠一覧</h2>
        <div class="navigation">
            <a href="#">←前月</a>
            <span style="margin: 0 1em;"><img src="{{ asset('images/calendar-icon.png') }}"> 2025/05</span>
            <a href="#">翌月→</a>
        </div>

        <table border="1" cellspacing="0" cellpadding="8" style="width:100%; margin-top: 20px;">
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
                        <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('m/d') }} ({{ $attendance['day'] }})</td>
                        <td>{{ $attendance['start_time'] }}</td>
                        <td>{{ $attendance['end_time'] }}</td>
                        <td>{{ $attendance['break_time'] }}</td>
                        <td>{{ $attendance['total_time'] }}</td>
                        <td><a href="{{ url('/attendance/' . $attendance['id']) }}">詳細</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection