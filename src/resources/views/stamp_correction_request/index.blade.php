{{-- resources/views/stamp_correction_request/index.blade.php --}}
@php
    $layoutView = $layout === 'layouts.admin' ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layoutView)

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
                @foreach($requests as $request)
                    {{-- 一般ユーザーは自分のデータのみ表示 --}}
                    @if(auth()->user()->is_admin || auth()->id() === $request->user_id)
                        <tr class="attendance__table-row">
                            <td>
                                {{ $request['status'] === 'pending' ? '承認待ち' : ($request['status'] === 'approved' ? '承認済み' : 'その他') }}
                            </td>
                            <td>{{ $request->user->name ?? '不明' }}</td>
                            <td>{{ \Carbon\Carbon::parse($request['target_date'])->format('Y/m/d') }}</td>
                            <td>{{ $request['reason'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($request['requested_at'])->format('Y/m/d') }}</td>
                            <td>
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.stamp_correction_request.approve', ['id' => $request['id'], 'reason' => $request['reason']]) }}"
                                        class="attendance__detail-link">詳細</a>
                                @else
                                    <a href="{{ route('attendance.show', ['id' => $request['id'], 'reason' => $request['reason'], 'target_date' => $request['target_date']]) }}"
                                        class="attendance__detail-link">詳細</a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection