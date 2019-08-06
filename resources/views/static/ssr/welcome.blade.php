{{--
This view drives the experience of clients with either javascript off, or poor implementation of it.
Essentially this is our Server Side Rendered page which itonically is what the React component is based on.
--}}
@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-4">
            @include('static.ssr.partials.sidebar')
        </div>
        <div class="column">
            <div class="box content">
                <div class="u-m-15">
                    @if (session('type')==='success')
                        <article class="message ubuntu-green killable" id="successbox">
                            <div class="message-header ubuntu-green">
                                <p>{{ session('message') }}</p>
                                <button class="delete" aria-label="delete"  onclick="$('#successbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                    @if (session('errors'))
                        <article class="message is-danger killable" id="errorbox">
                            <div class="message-header ubuntu-green">
                                <p>{{ session('message') }}</p>
                                <button class="delete" aria-label="delete"  onclick="$('#errorbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                </div>

                @foreach($proposals as $proposal)
                    <article class="post">
                        <a href="{{url('proposal/'.$proposal->slug)}}">
                        <span class="subtitle has-text-weight-bold">
                            {{$proposal->title}}
                        </span>
                        <span
                            class="tag is-small u-mleft-15 {{$proposal->category->class}}
                            {{$proposal->category->sub_class}}">
                            {{substr($proposal->category->short_title,0, 1)}}
                        </span>
                        </a>
                        <p>
                            {{$proposal->body}}
                        </p>
                        </a>
                        <div class="author controls u-mtop-10 u-mright-10 u-mbottom-10">
                            <i>Posted by</i>:&nbsp;
                            <b>
                                {{$proposal->user->display_name ?? $proposal->user->name }}
                            </b>
                            &nbsp;
                        </div>
                        <div class="aggs controls u-mbottom-20">
                            <form method="post" action="{{route('plainvote')}}">
                                <span class="numVotes">
                                    {{count($proposal->aggs) > 0 ? $proposal->aggs[0]->total_votes : ' No'}}
                                </span> votes
                                <span class="icon u-mleft-20">
                                    @if(!isset($proposal->myvote) || $proposal->myvote['vote'] == 0)
                                    <a href="{{route('plainvote')}}?pid={{$proposal->id}}&action=vote">
                                      <i class="fa fa-arrow-alt-circle-up">&nbsp;</i>
                                    </a>
                                    @else
                                    <a href="{{route('plainvote')}}?pid={{$proposal->id}}&action=vote">
                                       <i class="fa fa-minus-circle">&nbsp;</i>
                                    </a>
                                    @endif
                                </span>
                                <span className="numDislikes">
                                    <span className="icon u-mleft-10 u-mright-5">
                                        @if(isset($proposal->myvote) && $proposal->myvote['dislike'] > 0)
                                            <a href="#">
                                                <i class="fas fa-thumbs-up thumb-olive">&nbsp;</i>
                                            </a>
                                        @else
                                            <a href="#">
                                                <i class="fas fa-thumbs-down thumb-purple">&nbsp;</i>
                                            </a>
                                        @endif
                                    </span>
                                    {{count($proposal->aggs) > 0 ? $proposal->aggs[0]->total_dislikes : ' No'}}
                                </span> dislikes
                                <div class="'icon theworks">
                                    <a class="button" href="#">
                                    <span class="icon is-small">
                                       <i class="fas fa-print"> </i>
                                    </span>
                                    </a>&nbsp;
                                    <a class="button" href="#">
                                    <span class="icon is-small">
                                        <i class="fab fa-twitter"> </i>
                                    </span>
                                    </a>&nbsp;
                                    <a class="button" href="#">
                                    <span class="icon is-small">
                                        <i class="fab fa-facebook-f"> </i>
                                    </span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@stop
