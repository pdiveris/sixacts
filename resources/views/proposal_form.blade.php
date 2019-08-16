@extends('layouts.forum')
@section('content')
    <div class="columns">
        <div class="column is-12">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Propose action</p>
                </header>
                <div class="u-m-15">
                    @if ($pause === 'on')
                        <article class="message is-warning killable" id="successbox">
                            <div class="message-header is-warning">
                                <p>
                                    Back in 30 minutes... fag break... just counting the votes... between puffs
                                </p>
                                <button class="delete" aria-label="delete"  onclick="$('#successbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                    @if (session('errors'))
                        <article class="message is-danger killable" id="errorbox">
                            <div class="message-header ubuntu-green">
                                @foreach(session('errors')->getMessageBag() as $fld=>$error)
                                <p>{{ session('errors') }}</p>
                                @endforeach
                                <button class="delete" aria-label="delete"  onclick="$('#errorbox').hide();"
                                >
                                </button>
                            </div>
                        </article>
                    @endif
                    @if (session('type')==='success')
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
                <div class="card-content">
                    <form class="proposal-form" method="POST" action="{{route('propose')}}">
                        {{ csrf_field() }}
                        <div class="field is-horizontal">
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
                                            class="is-hidden"
                                            type="text"
                                            name="category_id"
                                            id="category_id"
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
                                <div class="tiny">Max 100 words</div>
                                <div class="tiny">
                                    <span id="actual" class="has-text-info actual">
                                        0
                                    </span> words so far
                                </div>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <textarea class="textarea is-medium"
                                       id="body"
                                       placeholder="Action details (up to 100 words)"
                                       name="body"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label"></div>
                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit"
                                           {{$pause === 'on' ? 'disabled="on"' : '' }}
                                           class="button is-primary"
                                        >Apply</button>
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
    window.onload = function() {
        if (window.jQuery) {
            const jq = window.jQuery;
            // attach to category buttons
            jq('.swap').on('click', function callback(event, data) {
                const elementId = event.target.id;
                const category_id = elementId.substring(elementId.indexOf('_')+1);
                jq('.swap').removeClass('full');
                jq('.swap').addClass('pale');

                jq('#'+event.target.id).removeClass('pale');
                jq('#category_id').val(category_id);
            });
            // attach to textarea for wordcount
            jq('#body').on('input', function callback(event, data) {
                let wCount = wordCountSum(countWords(jq('#body').val()));
                jq('#actual').html(wCount);
                if (wCount >= 90) {
                    // has-text-info has-text-danger
                    jq('#actual').removeClass('has-text-info').addClass('has-text-danger');
                } else {
                    jq('#actual').removeClass('has-text-danger').addClass('has-text-info');
                }
            });
        } else {
        }
    }
</script>
@stop
