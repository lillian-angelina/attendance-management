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
    <div class="content">
        <div class="attendance-detail">
            <h2><span class="attendance__title-line">|</span>勤怠詳細</h2>

            <div class="attendance-detail__group">
                <div class="attendance-detail__name">
                    <p class="attendance-detail__name-text">
                        名前
                        <span class="attendance-detail__name-value">
                            {{ $attendance->user->name ?? '不明' }}
                        </span>
                    </p>
                </div>

                {{-- 現在のデータの表示 --}}
                <div class="attendance-detail__display">
                    <p class="attendance-detail__ymd">
                        日付
                        <span class="attendance-detail__year">
                            {{ $year }}
                        </span>
                        <span class="attendance-detail__day">
                            {{ $monthDay }}
                        </span>
                    </p>
                </div>

                {{-- 修正フォーム --}}
                <form action="{{ route('attendance.update', ['attendance' => $attendance->id]) }}" method="POST"
                    class="attendance-detail__form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form__work-start" for="work_start">出勤・退勤</label>
                        <input class="form__work-start__input" type="time" name="work_start" id="work_start"
                            value="{{ old('work_end', \Carbon\Carbon::parse($attendance->work_start)->format('H:i')) }}">
                        @error('work_start')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <label class="form__work-end" for="work_end">～</label>
                        <input class="form__work-end__input" type="time" name="work_end" id="work_end"
                            value="{{ old('work_end', \Carbon\Carbon::parse($attendance->work_end)->format('H:i')) }}">
                        @error('work_end')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form__break-time" for="break_start_times_0">休憩</label>
                        <input class="form__break-time__input-start" type="time" name="break_start_times[]"
                            id="break_start_times_0"
                            value="{{ old('break_start_times.0', optional($attendance->breaks->get(0))->rest_start_time ? \Carbon\Carbon::parse($attendance->breaks->get(0)->rest_start_time)->format('H:i') : '') }}">
                        @error('break_start_times.0')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <label class="form__break-time_end" for="break_end_times_0">～</label>
                        <input class="form__break-time__input-end" type="time" name="break_end_times[]"
                            id="break_end_times_0"
                            value="{{ old('break_end_times.0', optional($attendance->breaks->get(0))->rest_end_time ? \Carbon\Carbon::parse($attendance->breaks->get(0)->rest_end_time)->format('H:i') : '') }}">
                        @error('break_end_times.0')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($attendance->breaks->count() < 2)
                        <div class="form-group">
                            <label class="form__break-time" for="break_start_1">休憩2</label>
                            <input class="form__break-time__input-start2" type="time" name="break_start_times[]"
                                id="break_start_1" value="00:00">
                            <label class="form__break-time_end" for="break_end_1">～</label>
                            <input class="form__break-time__input-end" type="time" name="break_end_times[]" id="break_end_1"
                                value="00:00">
                        </div>
                    @else
                        @foreach ($attendance->breaks as $index => $break)
                            <div class="form-group">
                                <label class="form__break-time" for="break_start_{{ $index }}">
                                    休憩{{ $loop->iteration + 1 }}
                                </label>
                                <input class="form__break-time__input-start2" type="time" name="break_start_times[]"
                                    id="break_start_{{ $index }}"
                                    value="{{ \Carbon\Carbon::parse($break->rest_start_time)->format('H:i') }}">
                                @error("break_start_times.$index")
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                
                                <label class="form__break-time_end" for="break_end_{{ $index }}">～</label>
                                <input class="form__break-time__input-end" type="time" name="break_end_times[]"
                                    id="break_end_{{ $index }}"
                                    value="{{ \Carbon\Carbon::parse($break->rest_end_time)->format('H:i') }}">
                                @error("break_end_times.$index")
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    @endif

                    <div class="form-group-reason">
                        <label class="form__reason" for="reason">備考</label>
                        <textarea class="form__reason__textarea" name="reason" id="reason"
                            rows="4">{{ old('reason', $correctionReason ?? '') }}</textarea>
                    </div>
                    @error('reason')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
            </div>
            @if (!$attendance->is_edited)
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">修正</button>
                </div>
            @else
                <p class="btn-text">※承認待ちのため修正はできません</p>
            @endif
            </form>
        </div>
    </div>
@endsection