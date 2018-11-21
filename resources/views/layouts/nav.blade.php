<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-info fixed-top navbar-transparent " color-on-scroll="400" style="background: orange">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="{{ route('home') }}"><span style="font-size: 150%">{{ config('app.name') }}</span></a>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navigation"
             data-nav-image={{ asset('img/blurred-image-1.jpg') }}>
            <ul class="navbar-nav">

                @if (Auth::check())
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link ml-auto" href="#">{{ Auth::user()->name }}</a>--}}
                    {{--</li>--}}

                    <li class="nav-item">
                        <div class="dropdown">
                            <a  href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown" id="navbarDropdownMenuLink1">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                                <li>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>


                @else

                    @if(Route::current()->getName() === 'session.create')

                        <li class="nav-item">
                            <a class="nav-link btn btn-info btn-round" href="{{ url('register') }}">
                                {{__('nav.signup')}}
                            </a>
                        </li>

                    @elseif(Route::current()->getName() === 'register.create')

                        <li class="nav-item">
                            <a class="nav-link btn btn-info btn-round" href="{{ url('login') }}">
                                {{__('nav.login')}}
                            </a>
                        </li>
                    @else

                        <li class="nav-item">
                            <a class="nav-link btn btn-info btn-round" href="{{ url('register') }}">
                                {{__('nav.signup')}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link btn btn-info btn-round" href="{{ url('login') }}">
                                {{__('nav.login')}}
                            </a>
                        </li>

                    @endif

                @endif

                    <li class="nav-item" style="margin-left: 50px">

                        <a id="lng" class="nav-link btn btn-info" href="{{ __('nav.lng') }}">
                            {{ __('nav.lang') }}
                        </a>

{{--                        <script>
                            $(document).ready(function () {
                                var ln = 0;
                                if (ln) {
                                    $("#lng").attr("href", "locale/en");
                                } else {
                                    $("#lng").attr("href", "locale/bn");
                                }

                                $("#lng").on('click', function () {

                                    if ($(this).attr('src') === "images/glyphicons/open.png") {
                                        $(this).attr('src', "images/glyphicons/close.png");
                                    } else {
                                        $(this).attr('src', "images/glyphicons/open.png");
                                    }

                                    var d = $(this).parent();
                                    var dParent = $(d).parent();
                                    var dParentParent = $(dParent).parent();
                                    $(dParentParent).next("div").toggle("slow");

                                });

                            });
                        </script>--}}

                    </li>

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->