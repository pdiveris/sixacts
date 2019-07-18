@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-3 u-mright-20">
            @include('partials.sidebar')
        </div>
        <div class="column is-1">
        </div>
        <div class="column is-8">
            <div id="proposals"></div>
        </div>
    </div>
@stop
