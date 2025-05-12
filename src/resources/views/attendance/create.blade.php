@extends('layouts.app')

@section('title', '出勤登録')

@section('content')
    <div class="attendance__container">
        <h1>勤怠登録</h1>

        {{-- 状態表示 --}}
        <p class="attendance__status">
            現在の状態: 
            @switch($status)
                @case('working')
                    <span style="color: green;">出勤中</span>
                    @break
                @case('resting')
                    <span style="color: orange;">休憩中</span>
                    @break
                @case('finished')
                    <span style="color: gray;">退勤済</span>
                    @break
                @default
                    <span style="color: red;">未出勤</span>
            @endswitch
        </p>

        {{-- 日付と時間の表示 --}}
        <div class="attendance__datetime">
            <p>日付：{{ \Carbon\Carbon::now()->format('Y年m月d日 (D)') }}</p>
            <p>時間：{{ \Carbon\Carbon::now()->format('H:i:s') }}</p>
        </div>

        {{-- ボタン切り替え --}}
        @if($status === 'none')
            {{-- 未出勤 → 出勤ボタン --}}
            <form method="POST" action="{{ route('attendance.clockIn') }}">
                @csrf
                <button type="submit" class="attendance__submit-button">出勤</button>
            </form>
        @elseif($status === 'working')
            {{-- 出勤中 → 休憩入／退勤 --}}
            <form method="POST" action="{{ route('attendance.clockOut') }}" style="display:inline;">
                @csrf
                <button type="submit" class="attendance__submit-button">退勤</button>
            </form>
            <form method="POST" action="{{ route('attendance.restStart') }}" style="display:inline;">
                @csrf
                <button type="submit" class="attendance__submit-button">休憩入</button>
            </form>
        @elseif($status === 'resting')
            {{-- 休憩中 → 休憩戻 --}}
            <form method="POST" action="{{ route('attendance.restEnd') }}">
                @csrf
                <button type="submit" class="attendance__submit-button">休憩戻</button>
            </form>
        @elseif($status === 'finished')
            {{-- 退勤済み --}}
            <p class="attendance__message">お疲れ様でした。</p>
        @endif
    </div>
@endsection
