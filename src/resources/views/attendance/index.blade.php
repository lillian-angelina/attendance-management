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

        {{-- 月切り替えナビゲーション --}}
        <div class="attendance__navigation">
            <a href="{{ route('attendance.list', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                class="attendance__nav--prev"><img src="{{ asset('images/←.png') }}" alt="#" class="icon">前日</a>

            <div class="attendance__nav--current">
                <img src="{{ asset('images/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
                <span>{{ $date->format('Y年n月j日') }}</span>
            </div>

            <a href="{{ route('attendance.list', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                class="attendance__nav--next">翌日<img src="{{ asset('images/→.png') }}" alt="#" class="icon"></a>
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
                @foreach($attendances as $attendance)
                    <tr class="attendance__table-row">
                        <td>{{ \Carbon\Carbon::parse($attendance['work_date'])->format('m/d') }} ({{ $attendance['day'] }})
                        </td>
                        <td>{{ $attendance['work_start'] }}</td>
                        <td>{{ $attendance['work_end'] }}</td>
                        <td>{{ $attendance['break_time'] }}</td>
                        <td>{{ $attendance['total_time'] }}</td>
                        <td>
                            <a href="{{ route('attendance.show', ['attendance' => $attendance->id]) }}"
                                class="attendance__detail-link">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection