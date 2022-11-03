<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <a href="{{url('terms')}}">Terms of Service</a> |
            <a href="{{url('privacy')}}">Privacy policy</a>
        </div>
        <div class="content has-text-centered">
            <div class="has-text-centered">
                <span class="title-6">Made by <b><a href="https://www.diveris.org/blog/">Petros Diveris</a></b></span>
                <br />
                <a href="{{url('plain')}}">Plain version</a>
                | <a href="{{url('/')}}">Home</a>
                | <a href="{{url('/about')}}">About</a>
                | <a href="{{url('/user/profile')}}">Profile</a>
            </div>
            <div class="has-text-centered">
                <span class="title-6">Licence: <b>Apache</b></span> |
                V
                <span class="title-6">
                    <b>
                        {{\App\Helpers\Utils::getRevisionString()}}
                        / {{app()->version()}}
                    </b>
                </span>
            </div>

        </div>
    </div>
</footer>
