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
            <a href="{{ url('/') }}" class="header_logo--icon">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo">
            </a>
        </div>

        <nav class="header_nav">
            <ul class="header_nav--ul">

                <li class="header_nav--attendance">
                    <a href="{{ url('/admin/attendance/list') }}">勤怠一覧</a>
                </li>

                <li class="header_nav--staff-list">
                    <a href="{{ url('/admin/staff/list') }}">スタッフ一覧</a>
                </li>

                <li class="header_nav--stamp_correction_request-list">
                    <a href="{{ url('/stamp_correction_request/list') }}">申請一覧</a>
                </li>

                @auth
                    <li class="header_nav--logout">
                        <form class="form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="header_form--logout">ログアウト</button>
                        </form>
                    </li>
                @else
                    <li class="header_nav--login">
                        <a href="{{ route('login') }}" class="header_form--login">ログイン</a>
                    </li>
                @endauth

                
            </ul>
        </nav>
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