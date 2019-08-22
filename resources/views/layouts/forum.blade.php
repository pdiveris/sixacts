<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{asset('images/favicon-16x16.png')}}" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}"/>
    <link rel="manifest" href="{{asset('/sixacts.webmanifest')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    @isset($croppie)
        <link rel="stylesheet" href="{{asset('js/croppie/croppie.css')}}">
        <script type="text/javascript" src="{{asset('js/croppie/jq.js')}}"></script>
        <script async type="text/javascript" src="{{asset('js/croppie/croppie.min.js')}}"></script>
    @endisset
    <script async type="text/javascript" src="{{asset('/js/sixacts.js')}}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/sixacts.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
        'user' => auth()->check() ? auth()->user()->id : null,
    ]) !!};
        window.keepalive = {!!json_encode(env('KEEP_ALIVE') ?? false)!!}
        window.showSplash = {!!json_encode( \App\Http\Controllers\SiteController::showModal())!!}
        window.sock = {!!json_encode(env('SOCKET_PROVIDER') ?? 'echo')!!}
        window.d = {!!json_encode(env('APP_DEBUG') ?? false )!!}
        window.token = {!!json_encode(\App\Http\Controllers\AuthController::getToken() )!!}
    </script>
    @include('feed::links')
</head>
<body>
<nav class="navbar is-white topNav">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{url('')}}">
                <img src="{{asset('images/6_acts_logo.jpg')}}" class="logo_diaroia">
            </a>
            <div class="navbar-burger burger" data-target="topNav">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div id="topNav" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item {{Site::menuClass('')}}" href="{{url('')}}">Home</a>
                <a class="navbar-item {{Site::menuClass('about')}}" href="/{{Site::menuLink('about')}}">
                    About
                </a>
                <a class="navbar-item {{Site::menuClass('categories')}}" href="/{{Site::menuLink('categories')}}">
                    Categories Explained
                </a>
                <a class="navbar-item {{Site::menuClass('nine_rules')}}" href="/{{Site::menuLink('nine_rules')}}">
                    9 Rules for the Six Acts
                </a>
                <a class="navbar-item {{Site::menuClass('register')}}" href="/{{Site::menuLink('register')}}">
                    Register
                </a>
                <a class="navbar-item {{Site::menuClass('user/profile')}}" href="/{{Site::menuLink('user/profile')}}">
                    Profile
                </a>
                <a class="navbar-item {{Site::menuClass('signin')}}" href="/{{Site::menuLink('signin')}}">
                    Sign in
                </a>
            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="field is-grouped">
                        <div class="control" >
                            @if(Auth::guest())
                            <a class="button is-size-6 is-outlined" href="{{Site::menuLink('login')}}">
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
                                            <a href="{{route('profile')}}">
                                            <img class="navatar"
                                                 src="{{ \App\Http\Controllers\StaticController::makeAvatar(Auth::user())}}"
                                            ></a>
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
<script async type="text/javascript" src="{{asset('/js/app.js')}}"></script>
<script async type="text/javascript" src="{{asset('js/bulma.js')}}"></script>
<!-- Matomo -->
<script type="text/javascript">
    var _paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="//metrics.definitio.org/metrics/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '{{env('MATOMO_SITE_ID')}}']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<!-- End Matomo Code -->
<!-- the sixacts -->
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-143185860-1']);
    _gaq.push(['_trackPageview']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www')
            + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<script async type="text/javascript" src="{{asset('/js/extras.js')}}"></script>
</body>
</html>
