<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Warehouse') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Warehouse') }} <img style="vertical-align: middle; height:30px" src="{{asset('warehouse.png')}}">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if(!Route::is('home'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Home</a>
                                </li>
                                @if(!Route::is('products'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('products')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Products</a>
                                </li>
                                @endif
                                @if(!Route::is('transfers'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('transfers')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Transfers</a>
                                    </li>
                                @endif
                                @if(!Route::is('clients'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('clients')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Clients</a>
                                    </li>
                                @endif
                                @if(!Route::is('suppliers'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('suppliers')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Suppliers</a>
                                    </li>
                                @endif
                                @if(!Route::is('finances') && in_array(Auth::user()->level, [1,2,3]))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('finances')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Finances</a>
                                    </li>
                                @endif
                                @if(!Route::is('employees') && in_array(Auth::user()->level, [1,2]))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('employees')}}"><i class="fas fa-2x fa-project-diagram hideMenuIcons"></i>Employees</a>
                                    </li>
                                @endif
                                <li class="nav-item"><a class="nav-link">{{" | "}}</a></li>


                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
@yield('scripts')
</html>
