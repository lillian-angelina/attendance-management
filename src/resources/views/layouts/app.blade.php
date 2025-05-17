<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('title')
    <title>勤怠管理システム</title>
    <link rel="stylesheet" href="{{ asset('css/layouts-common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
</head>

<body>
    @yield('css')
    <header class="header">
        <div class="header_logo">
            <a href="{{ url('/login') }}" class="header_logo--icon">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo">
            </a>
        </div>

        @if (!Request::is('register') && !Request::is('login') && !Request::is('verify-email'))
            <nav class="header_nav">
                <ul class="header_nav--ul">

                    @if (isset($status) && $status === 'finished')
                        {{-- 退勤後メニュー --}}
                        <li class="header_nav--attendance-list">
                            <a href="{{ url('/attendance/list') }}">今月の出勤一覧</a>
                        </li>

                        <li class="header_nav--stamp_correction_request-list">
                            <a href="{{ url('/stamp_correction_request/list') }}">申請一覧</a>
                        </li>
                    @else
                        {{-- 通常メニュー --}}
                        <li class="header_nav--attendance">
                            <a href="{{ url('/attendance') }}">勤怠</a>
                        </li>

                        <li class="header_nav--attendance-list">
                            <a href="{{ url('/attendance/list') }}">勤怠一覧</a>
                        </li>

                        <li class="header_nav--stamp_correction_request-list">
                            <a href="{{ url('/stamp_correction_request/list') }}">申請</a>
                        </li>
                    @endif

                        <li class="header_nav--logout">
                            <form class="form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="header_form--logout">ログアウト</button>
                            </form>
                        </li>
                </ul>
            </nav>
        @endif
    </header>

    <main>
        @yield('content')
        @yield('css')
    </main>

    <footer>

    </footer>

    @yield('js')
</body>

</html>