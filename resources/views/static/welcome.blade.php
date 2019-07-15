@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-3">
            @include('partials.sidebar')
        </div>
        <div class="column is-9">
            <div id="proposals"></div>
        </div>
    </div>
@stop
