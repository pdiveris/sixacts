{{--
Sidebar snippet
Author Petros Diveris <petros@diveris.org>
--}}
<a class="button is-primary is-block is-alt is-large" href="{{route('propose')}}">
    Propose Act
</a>
<aside class="menu">
    <p class="menu-label">
        Tags
    </p>
    <ul class="menu-list">
        @foreach($categories as $category)
            <li>
                <span class="tag {{$category->class}} is-medium {{$category->sub_class}}">
                    {{$category->short_title}}
                </span>
            </li>
        @endforeach
    </ul>
</aside>
