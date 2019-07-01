<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{asset('images/favicon-16x16.png')}}" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sixacts.css')}}">
</head>
<body>
<nav class="navbar is-white topNav">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{url('')}}">
                <img src="{{asset('images/sixacts_logo.png')}}" width="112" height="28">
            </a>
            <div class="navbar-burger burger" data-target="topNav">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div id="topNav" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="{{url('')}}">Home</a>
                <a class="navbar-item" href="{{url('about')}}">About</a>
                {{--
                                <a class="navbar-item" href="{{url('blog')}}">Blog</a>
                                <a class="navbar-item" href="{{url('search')}}">Search</a>
                --}}
                <a class="navbar-item" href="{{url('signin')}}">Sign in</a>
                <a class="navbar-item" href="{{url('register')}}">Register</a>
                <a class="navbar-item" href="{{url('terms')}}">Terms</a>
                <a class="navbar-item" href="{{url('privacy')}}">Privacy</a>

            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="field is-grouped">
                        <div class="control" >
                            @if(Auth::guest())
                            <a class="button is-size-6 is-outlined" href="{{'login'}}">
                                <span class="icon">
                                    <i class="fa fa-sign-in-alt"></i>
                                </span>
                                <span>Login</span>
                            </a>
                            @else
{{--
                                <h3>{{Auth::user()->email}}</h3>
--}}
                                <div class="rows">
                                    <div class="column" style="display: inline; ">
                                            <!-- Show image with default dimensions -->
                                            <img class="navatar"
                                                 src="{{ \App\Http\Controllers\StaticController::makeAvatar(Auth::user())}}"
                                            >
                                    </div>
                                    <div class="column" style="display: inline; ">
                                        <a href="{{'logout'}}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();
                                          ">
                                            <span>Sign out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<section class="container">
@yield('content')
</section>
@include('partials.footer')
<script async type="text/javascript" src="../js/bulma.js"></script>
</body>
</html>
