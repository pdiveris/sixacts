@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-9">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Your profile</p>
                </header>
                <div class="u-m-15">
                    @if (session('status'))
                        <div class="is-info alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="is-warning alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif
                </div>
                <div class="card-content">
                    <form class="login-form" method="POST" action="{{ route('profile') }}">
                        {{ csrf_field() }}
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Name</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input"
                                               id="name"
                                               type="text"
                                               name="name"
                                               value="{{$user->name ?? old('name') }}"
                                        required autofocus>
                                    </p>
                                    @if ($errors->has('email'))
                                        <p class="help is-danger">
                                            {{ $errors->first('name') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Display name</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input"
                                               id="display_name"
                                               type="text"
                                               name="display_name"
                                               value="{{$user->display_name ?? old('display_name') }}"
                                               required autofocus>
                                    </p>
                                    @if ($errors->has('email'))
                                        <p class="help is-danger">
                                            {{ $errors->first('display_name') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">E-Mail Address</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input" id="email" type="email" name="email"
                                               value="{{ $user->email ?? old('email') }}" required autofocus>
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
                                        <input
                                            class="input"
                                            id="password"
                                            type="password"
                                            value="{{$user->password}}"
                                            name="password" required>
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
                                <label class="label">Confirm password</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input"
                                               id="password2"
                                               type="password"
                                               value="{{$user->password}}"
                                               name="password2"
                                               required>
                                    </p>
                                    @if ($errors->has('password2'))
                                        <p class="help is-danger">
                                            {{ $errors->first('password2') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Avatar</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <img class="user_avatar"
                                         src="{{ \App\Http\Controllers\StaticController::makeAvatar(Auth::user())}}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label"></div>
                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-primary">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
