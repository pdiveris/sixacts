@extends('layouts.forum')
@section('content')
    <div class="columns">
        {{--
                <div class="column is-3">
                    @include('partials.sidebar')
                </div>
        --}}
        <div class="column is-9">
            <div class="content" >
                <div class="card"  style="min-height: 400px;">
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
                                        <div class="control is-inline">
                                            <!-- twitter button -->
                                            <a class="button is-medium" href="{{route('signin_twitter')}}">
                                            <span>
                                                <i class="fab fa-facebook-f"></i> twitter
                                            </span>
                                            </a>
                                        </div>
                                        <div class="control is-inline">
                                            <!-- twitter button -->
                                            <a class="button is-medium" href="{{route('signin_google')}}">
                                            <span>
                                                <i class="fab fa-facebook-f"></i> Google
                                            </span>
                                            </a>
                                        </div>
                                        <div class="control is-inline">
                                            <!-- fakbook button -->
                                            <a class="button is-medium" href="{{route('signin_facebook')}}">
                                            <span>
                                                <i class="fab fa-facebook-f"></i> facebook
                                            </span>
                                            </a>
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
    </div>
@stop