@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column">
            <div id="lista" data='{{ $listData }}'></div>
        </div>
        <div class="column is-8">
            <div id="tablets"></div>
        </div>
    </div>
@stop
