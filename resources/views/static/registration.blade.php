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
                    GDPR
                </div>
                <div class="links">
                    <a class="button is-info" href="{{url('')}}">HOME</a>
                    <a class="button is-info" href="{{route('signin')}}">SIGN IN</a>
                    <a class="button is-info" href="{{route('register')}}">REGISTER</a>
                    <a class="button is-info" href="{{route('terms')}}">TERMS</a>
                    <a class="button is-info" href="{{route('privacy')}}">PRIVACY</a>
                </div>
            </div>
        </div>
    </div>

@stop
