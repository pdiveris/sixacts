@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-4 u-mright-20">
            @include('partials.sidebar')
        </div>
        <div class="column">
            <div id="proposals"></div>
        </div>
    </div>
@stop
