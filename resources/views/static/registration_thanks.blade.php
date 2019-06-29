@extends('layouts.app')
@section('content')
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    6 Acts
                </h1>
            </div>
        </div>
    </section>
    <div class="columns is-marginless is-centered">
        <div class="column is-5">
            <div class="content">
                <div class="title m-b-md">
                    THE SIX ACTS PROJECT
                </div>
                <div class="subtitle m-b-md">
                    Thank you for joining
                </div>
                <div class="u-mtop-20">
                    Soon you will be redirected to the home page where you can see the current proposals,
                    up- and down-vote them, as well as add your own ones.
                </div>
                <div class="u-mtop-20">
                    Click <a href="{{url('')}}">here</a> if your browser isn't redirected to the main site after a few seconds.
                </div>
                <div class="links u-mtop-50">
                    <a class="button is-info" href="{{url('')}}">HOME</a>
                    <a class="button is-info" href="{{route('signin')}}">SIGN IN</a>
                    <a class="button is-info" href="{{route('register')}}">REGISTER</a>
                    <a class="button is-info" href="{{route('terms')}}">TERMS</a>
                    <a class="button is-info" href="{{route('privacy')}}">PRIVACY</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        var timer = setTimeout(function() {
            window.location='{{url('')}}'
        }, 5000);
    </script>
@stop
