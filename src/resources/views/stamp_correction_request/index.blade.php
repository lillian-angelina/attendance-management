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
        <h2>申請一覧</h2>

        <div style="margin-bottom: 15px;">
            <button>承認待ち</button>
            <button>承認済み</button>
        </div>

        <table border="1" cellspacing="0" cellpadding="8" style="width:100%;">
            <thead>
                <tr>
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
                    <tr>
                        <td>{{ $request['status'] }}</td>
                        <td>{{ $request['name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($request['target_date'])->format('Y/m/d') }}</td>
                        <td>{{ $request['reason'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($request['requested_at'])->format('Y/m/d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.stamp_correction_request.approve', $request['id']) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection