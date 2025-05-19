{{-- resources/views/attendance/show.blade.php --}}
@php
    $layoutView = $layout === 'layouts.admin' ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layoutView)

@section('title')
    <title>勤怠詳細</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <h2>勤怠詳細</h2>

        {{-- 現在のデータの表示 --}}
        <div class="attendance-detail__display">
            <p class="attendance-detail__ymd">日付<span class="attendance-detail__start">{{ \Carbon\Carbon::parse($attendance->work_start)->format('Y/m/d') }}
                ({{ \Carbon\Carbon::parse($attendance->work_start)->isoFormat('ddd') }})</span></p>
        </div>

        {{-- 修正フォーム --}}
        @if (!$attendance->is_edited)
            <form action="{{ route('attendance.update', ['attendance' => $attendance->id]) }}" method="POST"
                class="attendance-detail__form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="work_start">出勤・退勤</label>
                    <input type="time" name="work_start" id="work_start"
                        value="{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}">
                    <label for="work_end">～</label>
                    <input type="time" name="work_end" id="work_end"
                        value="{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}">
                </div>

                <div class="form-group">
                    <label for="break_time">休憩</label>
                    <input type="number" name="break_time" id="break_time" value="{{ $attendance->break_time ?? 60 }}">
                </div>

                <div class="form-group">
                    <label for="note">備考</label>
                    <textarea name="note" id="note">{{ $attendance->note }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">修正</button>
            </form>
        @else
            <p style="color: red;">※承認待ちのため修正はできません</p>
        @endif
    </div>
@endsection