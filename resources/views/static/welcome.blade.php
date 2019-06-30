@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-3">
            @include('partials.sidebar')
        </div>
        <div class="column is-9">
            <div class="box content">
                @foreach($proposals as $proposal)
                    <article class="post">
                        <h4>{{$proposal->title}}</h4>
                        <div class="media">
                            <div class="media-left">
                                <p class="image is-32x32">
                                    <img src="http://bulma.io/images/placeholders/128x128.png">
                                </p>
                            </div>
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <a href="#">
                                            {{\App\Http\Controllers\StaticController::authorLink($proposal)}}
                                        </a>
                                        replied 34 minutes ago &nbsp;
                                        <span class="tag">TAG</span>
                                    </p>
                                </div>
                            </div>
                            <div class="media-right">
                                <span class="has-text-grey-light"><i class="fa fa-comments"></i> 1</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@stop
