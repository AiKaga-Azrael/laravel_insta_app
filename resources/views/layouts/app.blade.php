<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @production
        <link rel="stylesheet" href="{{ asset('build/assets/app-66QQgIxc.css') }}">
        <script type="module" src="{{ asset('build/assets/app-D-03kJxt.js') }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction




    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- asset will look for public folder and then css -->
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <!-- search bar. show it when user logs in -->
                    @auth
                        @if (!request()->is('admin/*'))
                            <form action="{{ route('search') }}" class="d-flex me-auto mt-2 mt-md-0" role="search">
                                <input type="search" name="search" placeholder="Search..." class="form-control form-control-sm">
                            </form>
                        @endif                       
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
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
                        <ul class="navbar-nav ms-auto flex-row align-items-center gap-3 mt-2 mt-md-0">
                           {{-- Home --}}
                            <li class="nav-item" title="Home">
                                <a href="{{ route('index') }}" class="nav-link p-0">
                                    <i class="fa-solid fa-house text-dark icon-sm"></i>
                                </a>
                            </li>

                            {{-- Create Post --}}
                            <li class="nav-item" title="Create Post">
                                <a href="{{ route('post.create') }}" class="nav-link p-0">
                                    <i class="fa-solid fa-circle-plus text-dark icon-sm"></i>
                                </a>
                            </li>

                            {{-- Unread Messages Notification --}}
                            <li class="nav-item" title="Notification">
                                <a class="nav-link position-relative p-0" href="{{ route('chats.unreadmessages',Auth::id()) }}">
                                    <i class="fa-solid fa-envelope text-dark icon-sm"></i>
                                    @if(isset($unreadCount) && $unreadCount > 0)
                                            <span class="notification-badge position-absolute top-0 start-100 translate-middle">
                                            {{ $unreadCount }}
                                            </span>
                                    @endif
                                </a>
                            </li>

                            {{-- Friend request Notification --}}
                            <li class="nav-item" title="Friend Requests">
                                <a class="nav-link position-relative p-0" href="{{ route('friend.requests') }}">
                                    <i class="fa-solid fa-user-plus text-dark icon-sm"></i>
                                    @if(isset($requestCount) && $requestCount > 0)
                                            <span class="notification-badge position-absolute top-0 start-100 translate-middle">
                                            {{ $requestCount }}
                                            </span>
                                    @endif
                                </a>
                            </li>

                            {{-- Account --}}
                            <li class="nav-item dropdown">
                               <button id="account-dropdown" class="btn shadow-none nav-link p-0" data-bs-toggle="dropdown">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-sm">                           
                                    @else
                                        <i class="fa-solid fa-circle-user text-dark icon-sm"></i>
                                    @endif
                                </button>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account-dropdown">
                                    @can('admin')
                                        {{-- Admin --}}
                                        <a href="{{ route('admin.users')}}" class="dropdown-item">
                                            <i class="fa-solid fa-user-gear"></i> Admin
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endcan                                                                             
                                    
                                    {{-- Profile --}}
                                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                        <i class="fa-solid fa-circle-user"></i> Profile
                                    </a>

                                    {{-- Logout --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
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

        <main class="py-5">
            <div class="container">
                <div class="row g-4">
                    {{-- Admin Menu --}}
                    @if (request()->is('admin/*'))
                        <div class="col-12 col-lg-3">
                            <div class="list-group">
                                {{-- users --}}
                                <a href="{{ route('admin.users') }}" class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>

                                {{-- posts --}}
                                <a href=" {{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>

                                {{-- categories --}}
                                <a href="{{ route('admin.categories') }}" class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>                        
                        </div>                           
                    @endif                    

                    <div class="col-12 {{ request()->is('admin/*') ? 'col-lg-9' : '' }}">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
