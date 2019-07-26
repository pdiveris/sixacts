@extends('layouts.forum')

@section('content')
    <div class="container">
        <div class="columns is-marginless is-centered">
            <div class="column is-8">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">{{ __('Your API Access Token') }}</p>
                    </header>

                    <div class="card-content">
                        @if (session('error'))
                            <div class="notification">
                            </div>
                        @endif
                        <textarea rows="10" cols="120">{{$token}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
