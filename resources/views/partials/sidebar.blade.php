{{--
Sidebar snippet
Author Petros Diveris <petros@diveris.org>
--}}
<a class="button ubuntu-green is-block is-alt" href="{{route('propose')}}">
    Propose an Act
</a>
<aside class="menu">
    <p class="menu-label">
        Tags
    </p>
    <ul class="menu-list">
        @foreach($categories as $category)
            <li>
                <span class="tag {{$category->class}} {{$category->sub_class}}">
                    {{$category->short_title}}
                </span>
            </li>
        @endforeach
    </ul>
</aside>
