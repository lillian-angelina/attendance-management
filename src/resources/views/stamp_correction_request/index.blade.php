{{-- resources/views/stamp_correction_request/index.blade.php --}}
@php
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layout)

@section('title')
    <title>申請一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stamp_correction_request.css') }}">
@endsection

@section('content')
    <div class="stamp-correction-list">
        <h1 class="attendance__title">
            <span class="attendance__title-line">|</span>申請一覧
        </h1>

        <div class="attendance__navigation">
            <a
                href="{{ url('/stamp_correction_request/list?status=pending' . (request('query') ? '&query=' . urlencode(request('query')) : '')) }}">
                <button
                    class="btn button__nav1 {{ request('status') === 'pending' || request('status') === null ? 'button__nav--active' : '' }}">
                    承認待ち
                </button>
            </a>
            <a
                href="{{ url('/stamp_correction_request/list?status=approved' . (request('query') ? '&query=' . urlencode(request('query')) : '')) }}">
                <button class="btn button__nav2 {{ request('status') === 'approved' ? 'button__nav--active' : '' }}">
                    承認済み
                </button>
            </a>
        </div>

        <table class="attendance__table">
            <thead>
                <tr class="attendance__table-header">
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $attendanceRequest)

                        <tr class="attendance__table-row">
                            <td>{{ $attendanceRequest['status'] === 'pending' ? '承認待ち' : ($attendanceRequest['status'] === 'approved' ? '承認済み' : 'その他') }}
                            </td>
                            <td>{{ $attendanceRequest->user->name ?? '不明' }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendanceRequest['target_date'])->format('Y/m/d') }}</td>
                            <td>{{ $attendanceRequest['reason'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendanceRequest['requested_at'])->format('Y/m/d') }}</td>
                            <td>
                                @if ($isAdmin)
                                            <a href="{{ route('stamp_correction_request.approve.show', [
                                        'attendanceCorrectionRequest' => $attendanceRequest['id'],
                                        'attendance' => $attendanceRequest['id'],
                                        'reason' => $attendanceRequest['reason'],
                                        'target_date' => $attendanceRequest['target_date']
                                    ]) }}" class="attendance__detail-link">詳細</a>
                                @else
                                            <a href="{{ route('attendance.show', [
                                        'attendance' => $attendanceRequest['id'],
                                        'reason' => $attendanceRequest['reason'],
                                        'target_date' => $attendanceRequest['target_date']
                                    ]) }}" class="attendance__detail-link">詳細</a>
                                @endif
                            </td>
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection