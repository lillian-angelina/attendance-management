{{-- resources/views/attendance/create.blade.php --}}
@extends('layouts.app')

@section('title')
    <title>勤怠登録</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-create.css') }}">
@endsection

@section('content')
    <div class="attendance__content">
        <p class="attendance__status">
            @switch($status)
                @case('working')
                    <span>出勤中</span>
                    @break
                @case('resting')
                    <span>休憩中</span>
                    @break
                @case('finished')
                    <span>退勤済</span>
                    @break
                @default
                    <span>勤務外</span>
            @endswitch
        </p>

        <div class="attendance__datetime">
            <p class="attendance__datetime-ymd">{{ $today }}（{{ $weekday }}）</p>
            <p id="liveTime" class="attendance__datetime-hi">{{ \Carbon\Carbon::now()->format('H:i') }}</p>
        </div>

        @if($status === 'none')
            <form class="form-clockin" method="POST" action="{{ route('attendance.startWork') }}">
                @csrf
                <button type="submit" class="attendance__submit-button">出勤</button>
            </form>
        @elseif($status === 'working')
            <div class="form-submit">
                <form class="form-clockout" method="POST" action="{{ route('attendance.endWork') }}">
                    @csrf
                    <button type="submit" class="attendance__submit-button">退勤</button>
                </form>
                <form class="form-reststart" method="POST" action="{{ route('attendance.restStart') }}">
                    @csrf
                    <button type="submit" class="attendance__submit-button" style="color: #000000; background: #FFFFFF;">休憩入</button>
                </form>
            </div>
        @elseif($status === 'resting')
            <form class="form-restend" method="POST" action="{{ route('attendance.restEnd') }}">
                @csrf
                <button type="submit" class="attendance__submit-button">休憩戻</button>
            </form>
        @elseif($status === 'finished')
            <p class="attendance__message">お疲れ様でした。</p>
        @endif
    </div>
@endsection

@section('js')
<script>
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('liveTime').textContent = `${hours}:${minutes}`;
    }
    updateTime();
    setInterval(updateTime, 60000);
</script>
@endsection
