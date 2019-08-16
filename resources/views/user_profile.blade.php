@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-12">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Your profile</p>
                </header>
                <div class="u-m-15">
                    @if (session('errors'))
                        <article class="message is-danger killable" id="errorbox">
                            <div class="message-header is-danger">
                                <p>
                                    @foreach(session('errors')->getMessageBag()->getMessages() as $field=>$messages)
                                    {{$messages[0]}}<br/>
                                    @endforeach
                                </p>
                                <button class="delete" aria-label="delete"  onclick="$('#errorbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                    @if (session('success'))
                        <article class="message is-danger killable" id="successbox">
                            <div class="message-header ubuntu-green">
                                <p>{{ session('success') }}</p>
                                <button class="delete" aria-label="delete"  onclick="$('#successbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                    @if (session('type')==='warning')
                        <article class="message is-warning killable" id="warningbox">
                            <div class="message-header is-warning">
                                <p>{{ session('warning') }}</p>
                                <button class="delete" aria-label="delete"  onclick="$('#warningbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                </div>
                <div class="card-content">
                    <form class="login-form" method="POST" action="{{ route('post_profile') }}">
                        {{ csrf_field() }}
                        <div class="field is-horizontal">
                            <div class="registration field-label">
                                <label class="label">
                                    Name
                                </label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input"
                                           id="name"
                                           type="text"
                                           name="name"
                                           value="{{$user->name ?? old('name') }}"

                                           autofocus
                                        >
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
                            <div class="registration field-label">
                                <label class="label">Display name</label>
                                <p style="font-size: 0.7em;" class="small-text text-purple">
                                    If set it will be used to identify you with the post instead of your name.
                                </p>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input
                                            class="input"
                                            id="display_name"
                                            type="text"
                                            name="display_name"
                                            value="{{$user->display_name ?? old('display_name') }}"

                                        >
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
                            <div class="registration field-label">
                                <label class="label">E-Mail Address</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input
                                            class="input"
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ $user->email ?? old('email') }}"
                                        >
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
                            <div class="registration field-label">
                                <label class="label">Password</label>
                                <p style="font-size: 0.7em;" class="small-text text-purple">
                                    Do not put a password here <b>unless you want to changed it</b>
                                </p>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input
                                            class="input"
                                            id="password"
                                            type="password"
                                            value=""
                                            name="password"
                                        >

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
                            <div class="registration field-label">
                                <label class="label">Confirm password</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                    </p>
                                    <input
                                        class="input"
                                        id="password2"
                                        type="password"
                                        value=""
                                        name="password2"
                                    >
                                @if ($errors->has('password2'))
                                        <p class="help is-danger">
                                            {{ $errors->first('password2') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
{{--

                        <div class="field is-horizontal">
                            <div class="registration field-label">
                                <label class="label">Avatar</label>
                            </div>
                            <div class="field-body">
                                <input
                                    class="input"
                                    type="file"
                                    value="text"
                                    name="local_avatar"
                                >

                            </div>
                        </div>

--}}
                        <div class="field is-horizontal">
                            <div class="registration field-label"></div>
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
