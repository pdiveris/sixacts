@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-4 u-mright-20">
            @include('partials.sidebar')
        </div>
        <div class="column">
            <div id="splash"></div>
            <div id="proposals">
                <div class="column">
                    <div class="content">
                        @foreach($proposals as $proposal)
                            <article class="post">
                                <a href="{{url('proposal/'.$proposal->slug)}}">
                                <span class="subtitle has-text-weight-bold">
                                    {{$proposal->title}}
                                </span>
                                <span
                                  class="tag is-small u-mleft-15 {{$proposal->category->class}}
                                  {{$proposal->category->sub_class}}"
                                >
                                {{substr($proposal->category->short_title,0, 1)}}
                                </span>
                                </a>
                                <p>
                                    {{$proposal->body}}
                                </p>
                                </a>
                                <div class="media">
                                    <div class="media-left">
                                        <p class="image is-32x32">
                                            <img src="https://bulma.io/images/placeholders/128x128.png">
                                        </p>
                                    </div>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <a href="#">
                                                    {{\App\Http\Controllers\StaticController::authorLink($proposal)}}
                                                </a>
                                                replied 34 minutes ago &nbsp;
                                                <span class="tag">TAG
                                        </span>
                                                <span>
                                            @if(count($proposal->aggs))

                                            @endif
                                        </span>
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
        </div>
    </div>
@stop
