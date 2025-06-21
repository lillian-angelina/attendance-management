{{-- resources/views/stamp_correction_request/approve.blade.php --}}
@extends('layouts.admin')

@section('title')
    <title>修正申請承認 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-stamp-correction-request-approve.css') }}">
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
                </div>

                {{-- 現在のデータの表示 --}}
                <div class="attendance-detail__display">
                    <p class="attendance-detail__ymd">
                        日付
                        <span class="attendance-detail__year">
                            {{ $year }}年
                        </span>
                        <span class="attendance-detail__day">
                            {{ $monthDay }}
                        </span>
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('stamp_correction_request.approve', ['attendanceCorrectionRequest' => $attendanceCorrectionRequest->id]) }}">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label class="form__work-start" for="work_start">出勤・退勤</label>
                        <input class="form__work-start__input" type="time" name="work_start" id="work_start"
                            value="{{ old('work_start', $attendance->work_start ? \Carbon\Carbon::parse($attendance->work_start)->format('H:i') : '') }}">
                        @error('work_start')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <label class="form__work-end" for="work_end">～</label>
                        <input class="form__work-end__input" type="time" name="work_end" id="work_end"
                            value="{{ old('work_end', $attendance->work_end ? \Carbon\Carbon::parse($attendance->work_end)->format('H:i') : '') }}">
                    </div>

                    <div class="form-group">
                        <label class="form__break-time" for="break_time">休憩</label>
                        <input class="form__break-time__input-start" type="time" name="break_start_times[]"
                            id="break_start_0"
                            value="{{ old('break_start_times.0', optional($attendance->breaks->first())->rest_start_time ? \Carbon\Carbon::parse($attendance->breaks->first()->rest_start_time)->format('H:i') : '') }}">
                        @error('break_start_times.0')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <label class="form__break-time_end" for="break-time_end">～</label>
                        <input class="form__break-time__input-end" type="time" name="break_end_times[]" id="break_end_0"
                            value="{{ old('break_end_times.0', optional($attendance->breaks->last())->rest_end_time ? \Carbon\Carbon::parse($attendance->breaks->last()->rest_end_time)->format('H:i') : '') }}">
                        @error('break_end_times.0')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($attendance->breaks->count() < 2)
                        @php $index = 1; @endphp
                        <div class="form-group">
                            <label class="form__break-time" for="break_start_1">休憩2</label>
                            <input class="form__break-time__input-start2" type="time" name="break_start_times[]"
                                id="break_start_{{ $index }}" value="{{ old("break_start_times.$index", '00:00') }}">
                            <label class="form__break-time_end" for="break_end_1">～</label>
                            <input class="form__break-time__input-end" type="time" name="break_end_times[]"
                                id="break_end_{{ $index }}" value="{{ old("break_end_times.$index", '00:00') }}">
                        </div>
                    @else
                        @foreach ($attendance->breaks as $index => $break)
                            <div class="form-group">
                                <label class="form__break-time" for="break_start_{{ $index }}">
                                    休憩{{ $loop->iteration + 1 }}
                                </label>
                                <input class="form__break-time__input-start2" type="time" name="break_start_times[]"
                                    id="break_start_{{ $index }}"
                                    value="{{ old("break_start_times.$index", \Carbon\Carbon::parse($break->rest_start_time)->format('H:i')) }}">
                                @error("break_start_times.$index")
                                    <div class="error-message">{{ $message }}</div>
                                @enderror

                                <label class="form__break-time_end" for="break_end_{{ $index }}">～</label>
                                <input class="form__break-time__input-end" type="time" name="break_end_times[]"
                                    id="break_end_{{ $index }}"
                                    value="{{ old("break_end_times.$index", \Carbon\Carbon::parse($break->rest_end_time)->format('H:i')) }}">
                                @error("break_start_times.$index")
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    @endif

                    <div class="form-group-reason">
                        <label class="form__reason" for="reason">備考</label>
                        <textarea class="form__reason__textarea" name="reason" id="reason" class="form-control"
                            rows="4">{{ old('reason', $correctionReason ?? $attendance->reason) }}</textarea>
                    </div>
                    @error('reason')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    @if($attendanceCorrectionRequest->status === 'approved')
                        <div class="button-group">
                            <p class="btn-text">承認済み</p>
                        </div>
                    @else
                        <div class="button-group">
                            <button type="submit" class="btn">承認</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection