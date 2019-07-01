@extends('layouts.forum')
@section('content')

<div class="columns">
    <div class="column is-9">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Reset Password</p>
            </header>
            <div class="u-m-15">
                @if (session('status'))
                    <div class="notification">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <div class="card-content">
                <form class="login-form" method="POST" action="action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <div class="field is-horizontal">
                        <div class="field-label">
                            <label class="label">E-Mail Address</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <input class="input" id="email" type="email" name="email"
                                           value="{{ old('email') }}" required autofocus>
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
                        <div class="field-label"></div>

                        <div class="field-body">
                            <div class="field is-grouped">
                                <div class="control">
                                    <button type="submit" class="button is-primary">Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
