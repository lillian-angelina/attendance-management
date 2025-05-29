{{-- resources/views/stamp_correction_request/approve.blade.php --}}
@extends('layouts.admin')

@section('title')
    <title>修正申請承認 | 勤怠管理システム</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-stamp-correction-request-approve.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1 class="text-2xl font-bold mb-6">修正申請承認</h1>

        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-4">勤怠詳細</h2>

            <div class="mb-4">
                <label class="font-semibold">名前：</label>
                <span>{{ $attendanceCorrectionRequest->user->name }}</span>
            </div>

            <div class="mb-4">
                <label class="font-semibold">日付：</label>
                <span>{{ \Carbon\Carbon::parse($attendanceCorrectionRequest->date)->format('Y年m月d日') }}</span>
            </div>

            <div class="mb-4">
                <label class="font-semibold">出勤・退勤：</label>
                <span>{{ $attendanceCorrectionRequest->start_time }} ～ {{ $attendanceCorrectionRequest->end_time }}</span>
            </div>

            @if ($attendanceCorrectionRequest->attendance && $attendanceCorrectionRequest->attendance->breaks->isNotEmpty())
                <div class="mb-4">
                    <label class="font-semibold">休憩：</label>
                    <ul class="list-disc pl-5">
                        @foreach ($attendanceCorrectionRequest->attendance->breaks as $index => $break)
                            <li>
                                休憩{{ $index + 1 }}：{{ $break->rest_start_time }} ～ {{ $break->rest_end_time }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="font-semibold">備考：</label>
                <p class="whitespace-pre-wrap">{{ $attendanceCorrectionRequest->note }}</p>
            </div>

            <form method="POST"
                action="{{ route('admin.stamp_correction_request.approve', $attendanceCorrectionRequest->id) }}">
                @csrf
                @method('POST')
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">承認</button>
            </form>
        </div>
    </div>
@endsection