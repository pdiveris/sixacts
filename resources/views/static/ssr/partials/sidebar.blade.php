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
    <a class="button is-size-6" href="https://twitter.com/ActsSix" target="sothial">
        <span>
            <i class="fab fa-twitter"></i>
        </span>
    </a>
    <a class="button is-size-6" href="https://twitter.com/ActsSix" target="sothial">
        <span>
            <i class="fab fa-facebook-f"></i>
        </span>
    </a>
</aside>
