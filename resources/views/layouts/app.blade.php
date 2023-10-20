<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @php
                                $board_users_model = Auth::user()
                                    ->boardUsers()
                                    ->where('user_id', Auth::user()->id)
                                    ->where('active', 0);
                                $count_noti = $board_users_model->count();
                                $board_users = $board_users_model->get();
                                // dd($count_noti);
                            @endphp
                            @if ($count_noti > 0)
                                <li class="nav-item dropdown">
                                    <a id="navNotiDropdown" class="nav-link dropdown-toggle " href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <span class="badge rounded-pill bg-danger">{{ $count_noti }}</span>

                                        {{-- {{ Auth::user()->boardUsers()->where('user_id', Auth::user()->id)->where('active', 0)->count() }} --}}
                                    </a>
                                    {{-- @dd($board_user) --}}
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navNotiDropdown">
                                        <div class="d-flex flex-column">
                                            @foreach ($board_users as $board_user)
                                                <div class="p-2">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="dropdown-item text-left">
                                                            invite to: {{ $board_user->board->title }}
                                                        </div>
                                                        <div class="dropdown-item text-right">
                                                            <form id="accept-form_+ {{ $board_user->id }}"
                                                            action="{{ route('board_users.update', $board_user->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                            <input type="hidden" name="active" value="1">
                                                            <input type="hidden" name="id" value="{{ $board_user->id }}">
                                                            <input type="hidden" name="board_id"
                                                                value="{{ $board_user->board_id }}">
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $board_user->user_id }}">

                                                        </form>
                                                            <a class="btn btn-warning text-right"
                                                                href="{{ route('board_users.update', $board_user->id) }}"
                                                                method="POST"
                                                                onclick="event.preventDefault();
                                                                document.getElementById('accept-form_+ {{ $board_user->id }}').submit();"
                                                                >
                                                                Accept
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">


                                                    </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                                {{-- @else --}}
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-bell"></i>
                                    <span class="badge badge-light bg-primary badge-xs">{{auth()->user()->unreadNotifications->count()}}</span>
                                </a>
                                <ul class="dropdown-menu w-100">
                                            @if (auth()->user()->unreadNotifications)
                                            <li class="d-flex justify-content-center pb-2">
                                                <a href="{{route('mark-as-read')}}" class="btn btn-primary btn-sm">Mark All as Read</a>
                                            </li>
                                            @endif

                                            @foreach (auth()->user()->unreadNotifications as $notification)
                                            <a href="#" class="text-primary text-decoration-none bg-light"><li class="p-1 text-primary border-top"> {{$notification->data['data']}}</li></a>
                                            @endforeach
                                            @foreach (auth()->user()->readNotifications as $notification)
                                            <a href="#" class="text-secondary text-decoration-none px-5"><li class="p-1 text-secondary border-top"> {{$notification->data['data']}}</li></a>
                                            @endforeach
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
