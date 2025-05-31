{{-- resources/views/stamp_correction_request/approve.blade.php --}}
@extends('layouts.admin')

@section('title')
    <title>修正申請承認 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-stamp-correction-request-approve.css') }}">
@endsection

@section('content')
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
                        {{ $year }}
                    </span>
                    <span class="attendance-detail__day">
                        {{ $monthDay }}
                    </span>
                </p>
            </div>

            <form method="POST"
                action="{{ route('admin.stamp_correction_request.approve', ['attendanceCorrectionRequest' => $attendanceCorrectionRequest->id]) }}">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label class="form__work-start" for="work_start">出勤・退勤</label>
                    <input class="form__work-start__input" type="time" name="work_start" id="work_start"
                        value="{{ optional($attendance->work_start) ? \Carbon\Carbon::parse($attendance->work_start)->format('H:i') : '' }}">
                    <label class="form__work-end" for="work_end">～</label>
                    <input class="form__work-end__input" type="time" name="work_end" id="work_end"
                        value="{{ optional($attendance->work_end) ? \Carbon\Carbon::parse($attendance->work_end)->format('H:i') : '' }}">
                </div>

                <div class="form-group">
                    <label class="form__break-time" for="break_time">休憩</label>
                    <input class="form__break-time__input-start" type="time" name="break_time" id="break_time"
                        value="{{ optional($attendance->breaks->first())->rest_start_time ? \Carbon\Carbon::parse($attendance->breaks->first()->rest_start_time)->format('H:i') : '' }}">
                    <label class="form__break-time_end" for="break-time_end">～</label>
                    <input class="form__break-time__input-end" type="time" name="break_time" id="break_time"
                        value="{{ optional($attendance->breaks->last())->rest_end_time ? \Carbon\Carbon::parse($attendance->breaks->last()->rest_end_time)->format('H:i') : '' }}">
                </div>

                @foreach ($attendance->breaks as $break)
                    <div class="form-group">
                        <label class="form__break-time" for="break_time_{{ $loop->index }}">
                            休憩{{ $loop->iteration + 1 }}
                        </label>
                        <input class="form__break-time__input-start2" type="time" name="break_time[]"
                            id="break_time_{{ $loop->index }}"
                            value="{{ \Carbon\Carbon::parse($break->rest_start_time)->format('H:i') }}">
                        <label class="form__break-time_end" for="break-time_end">～</label>
                        <input class="form__break-time__input-end" type="time" name="break_time" id="break_time"
                            value="{{ optional($attendance->breaks->last())->rest_end_time ? \Carbon\Carbon::parse($attendance->breaks->last()->rest_end_time)->format('H:i') : '' }}">
                    </div>
                @endforeach

                <div class="form-group-note">
                    <label class="form__note" for="note">備考</label>
                    <textarea class="form__note__textarea" name="note" id="note" class="form-control"
                        rows="4">{{ old('note', $correctionReason ?? $attendance->note) }}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">承認</button>
                </div>
            </form>
        </div>
    </div>
@endsection