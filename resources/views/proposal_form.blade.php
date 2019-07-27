@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-9">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Propose action</p>
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
                    @if (session('errors'))
                        <div class="is-error alert-warning">
                            {{ session('errors') }}
                        </div>
                    @endif
                </div>
                <div class="card-content">
                    <form class="proposal-form" method="POST" action="{{route('propose')}}">
                        {{ csrf_field() }}
                        <div class="field is-horizontal">
                            <input type="hidden" name="category_id" id="category_id"  value="3" />
                            <div class="field-label">
                                <label class="label">Category</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <div class="buttons" id="propose_buttons">
                                    @foreach($categories as $c)
                                    <a href="#"
                                       id="button_{{$c->id}}"
                                       class="button swap {{$c->class}} {{$c->selected? 'full' : 'pale' }} u-m-5"
                                     >{{$c->short_title}}</a>
                                    @endforeach
                                        <input
                                            type="hidden"
                                            name="categoryId"
                                            id="categoryId"
                                            value="false"
                                        />

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Summary title</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input
                                            class="input"
                                            id="title"
                                            type="title"
                                            placeholder="Summary title"
                                            name="title"
                                            value="{{ old('title') }}" required autofocus>
                                    </p>
                                    @if ($errors->has('title'))
                                        <p class="help is-danger">
                                            {{ $errors->first('title') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Detailed action</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <textarea class="textarea"
                                       placeholder="Action details (up to 200 words)"
                                       name="body"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label"></div>
                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-primary">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    cash(function () {
        cash('.button').on ( 'click', function callback ( event, data ) {
            let elementId = event.target.id;
            let categoryId = elementId.substring(elementId.indexOf('_')+1);

            cash('.swap').removeClass('full');
            cash('.swap').addClass('pale');

            cash('#'+event.target.id).removeClass('pale');
            cash('#categoryId').val(categoryId);

        }) // => collection
    });
    </script>
@stop
