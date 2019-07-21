@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-9">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Broadcast message</p>
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
                    <form class="login-form" method="POST" action="{{ route('postmessage') }}">
                        {{ csrf_field() }}

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Message</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input
                                            class="input" id="message"
                                            name="message"
                                            value="{{ old('message') }}" required autofocus>
                                    </p>

                                    @if ($errors->has('message'))
                                        <p class="help is-danger">
                                            {{ $errors->first('message') }}
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
                                        <button type="submit" class="button is-primary">Send</button>
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

