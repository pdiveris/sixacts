<a class="button ubuntu-green is-block is-alt" href="{{url('propose')}}">
    Propose Act
</a>
<aside class="menu">
    <p class="menu-label">
        Tags
    </p>
    <ul class="categoriesList">
        @foreach($categories as $i=>$category)
            <li>
                <span class="xbutton">
                    <a id="Katze_{{$i+1}}"
                       class="button {{$category->class}}">
                        {{$category->short_title}}
                    </a>
                </span>
            </li>
        @endforeach
    </ul>
    <p class="menu-label">
        Six Acts on Social
    </p>
    <a class="button is-size-6" href="https://twitter.com/ActsSix/" target="sothial">
        <span>
            <i class="fab fa-twitter"> </i>
        </span>
    </a>
    <a class="button is-size-6 u-mbottom-10" href="https://www.facebook.com/groups/899694013703882/" target="sothial">
        <span>
            <i class="fab fa-facebook-f"> </i>
        </span>
    </a>
    <p class="menu-label">
        View by
    </p>
    <p>
        <span class="is-small u-m-5">
            <a class="button is-small {{$filter === 'most' ? 'is-dark' : 'text-purple' }}"
               href="{{route('plain')}}?filter=most">Most votes</a>
        </span>
        <span class="is-small u-m-5">
            <a class="button is-small {{$filter === 'recent' ? 'is-dark' : 'text-purple' }}"
               href="{{route('plain')}}?filter=recent">Most recent</a>
        </span>
    </p>
    <p class="u-mtop-10">
        <span class="is-small u-m-5">
            <a class="button is-small {{$filter === 'current' ? 'is-dark' : 'text-purple' }}"
               href="{{route('plain')}}?filter=current">Current document</a>
        </span>
    </p>
{{--
    <p class="menu-label">
        Recent Posts
    </p>
     @foreach(\App\Http\Controllers\SiteController::getTwitts() as $twit)
        <div class="card twitterbox">
            <div class="card-content">
                <p class="subtitle is-7">
                <a href="{{$twit->real_url ?? '#'}}">
                {{$twit->text}}
                </a>
                </p>
                <p class="subtitle is-7">
                {{$twit->user->screen_name}}
                </p>
            </div>
        </div>
    @endforeach
--}}
</aside>
