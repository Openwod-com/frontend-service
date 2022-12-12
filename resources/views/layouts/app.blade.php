<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="Making training available for everyone!">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"> --}}
    @vite(['resources/css/app.scss'])
    <title>OpenWOD</title>
    @yield('head')
</head>
<body>
    <!-- Scripts -->
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script> --}}
    @vite(['resources/js/app.js'])
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> -->


    @if (auth()->user())
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
       <div class="d-flex flex-grow-1">
           <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
           <a href="/"><h1 class="navbar-brand"><img src="/favicon.ico" class="img-logo" alt=""> @yield('title', 'OpenWOD')</h1></a>
            <div class="w-100 text-right">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <div class="collapse navbar-collapse flex-grow-1 text-right" id="navbarNav">
            <ul class="navbar-nav ml-auto flex-nowrap">
                <li class="nav-item {{ Request::is('/') || Request::is('activities') ? 'active' : '' }}">
                    <a class="nav-link" href="/activities">Activities</a>
                </li>
                <li class="nav-item {{ Request::is('gyms')  ? 'active' : '' }}">
                    <a class="nav-link" href="/gyms">Gyms</a>
                </li>
                <li class="nav-item {{ Request::is('members')  ? 'active' : '' }}">
                    <a class="nav-link" href="/members">Members</a>
                </li>
                <li class="nav-item {{ Request::is('about')  ? 'active' : '' }}">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <form id="logout-form" class="form-inline" action="/logout" method="POST">
                        @csrf
                        <button class="btn btn-link nav-link" type="submit">Log Out</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    @endif

    <div class="container-fluid">
        @yield('content')
    </div>
    @yield('scripts')
    @yield('scripts2')
    @yield('scripts3')

</body>
</html>
