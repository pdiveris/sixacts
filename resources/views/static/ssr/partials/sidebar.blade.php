<a class="button is-primary is-block is-alt is-large" href="#">
    Propose Act
</a>
<aside class="menu">
    <p class="menu-label">
        Tags
    </p>
    <ul class="menu-list">
        @foreach($categories as $category)
            <li>
                <span class="tag {{$category->class}} is-medium {{$category->sub_class}}">{{$category->short_title}}</span>
            </li>
        @endforeach
        {{--
                <li><span class="tag is-primary is-medium ">Dashboard</span></li>
                <li><span class="tag is-link is-medium ">Customers</span></li>
                <li><span class="tag is-light is-danger is-medium ">Authentication</span></li>
                <li><span class="tag is-dark is-medium ">Payments</span></li>
                <li><span class="tag is-success is-medium ">Transfers</span></li>
                <li><span class="tag is-warning is-medium ">Balance</span></li>
                <li><span class="tag is-medium ">Question</span></li>
        --}}
    </ul>
</aside>
