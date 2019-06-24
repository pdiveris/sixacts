@extends('layouts.app')

@section('content')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Login
                </h1>
            </div>
        </div>
    </section>

    <div class="columns is-marginless is-centered">
        <div class="column is-5">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Sign In</p>
                </header>
                <div class="card-content">
                    <form class="login-form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="field is-horizontal">
                            <div class="field-label"></div>

                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <!-- twitter button -->
                                        <a class="button" href="{{route('signin_twitter')}}">TWITTER</a>
                                    </div>
                                    <div class="control">
                                        <!-- twitter button -->
                                        <a class="button" href="{{route('signin_google')}}">GOOGLE</a>

                                    </div>
{{--
                                    <div class="control">
                                        <a href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </div>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
