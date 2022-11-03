@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column">
                <div class="box content" >
                    <h1 class="title is-4">
                        {{$proposal->title}}
                    </h1>
                    <p>
                    {{$proposal->body}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop
