@extends('layouts.forum')
@section('content')
    <div class="columns">
        {{--
                <div class="column is-3">
                    @include('partials.sidebar')
                </div>
        --}}
        <div class="column is-9">
            <div class="content">
                <div class="card"  style="min-height: 400px;">
                    <header class="card-header">
                        <p class="card-header-title">Register</p>
                    </header>
                    <div class="card-content">
                        <form class="register-form" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">Name</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input
                                                class="input"
                                                id="name"
                                                type="name"
                                                name="name"
                                                value="{{ old('name') }}"
                                                required autofocus
                                            />
                                        </p>

                                        @if ($errors->has('name'))
                                            <p class="help is-danger">
                                                {{ $errors->first('name') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">E-mail Address</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input
                                                class="input"
                                                id="email"
                                                type="text"
                                                name="email"
                                                autocomplete="false"
                                                value="{{old('email')}}" required autofocus>
                                        </p>

                                        @if ($errors->has('email'))
                                            <p class="help is-danger">
                                                {{ $errors->first('email') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">Password</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input class="input" id="password" type="password" name="password" required>
                                        </p>

                                        @if ($errors->has('password'))
                                            <p class="help is-danger">
                                                {{ $errors->first('password') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">Confirm Password</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input class="input" id="password-confirm" type="password"
                                                   name="password_confirmation" required>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label"></div>

                                <div class="field-body">
                                    <div class="field is-grouped">
                                        <div class="control">
                                            <button type="submit" class="button is-primary">Register</button>
                                        </div>
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
