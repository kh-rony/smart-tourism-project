@extends('layouts.master')

@section('content')

    <div class="section section-signup"
         style="background-image: url({{ asset('img/login.jpg') }}); background-size: cover; background-position: top center;">
        <div class="container">
            <div class="row">
                <div class="card card-signup" data-background-color="orange">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form" method="POST" action="{{ route('login') }}">

                        {{ csrf_field() }}

                        <div class="header text-center">
                            <h4 class="title title-up">Log In</h4>
                            <div class="social-line">
                                <a href="{{ url('/auth/facebook') }}" class="btn btn-neutral btn-facebook btn-icon btn-round">
                                    <i class="fa fa-facebook-square"></i>
                                </a>
                                <a href="{{ url('/auth/twitter') }}" class="btn btn-neutral btn-twitter btn-icon btn-lg btn-round">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                <a href="{{ route('socialauth', 'google') }}" class="btn btn-neutral btn-google btn-icon btn-round">
                                    <i class="fa fa-google"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="input-group form-group-no-border">
                                        <span class="input-group-addon">
                                            <i class="now-ui-icons ui-1_email-85"></i>
                                        </span>
                                <input type="email" class="form-control" name="email" placeholder="Email..." value="{{ old('email') }}" required>

                            </div>

                            <div class="input-group form-group-no-border">
                                        <span class="input-group-addon">
                                            <i class="now-ui-icons text_caps-small"></i>
                                        </span>
                                <input type="password" class="form-control" name="password" placeholder="Password..." required>

                            </div>

                            @if ($errors->has('unauthenticated'))
                                <p>
                                    <span>{{ $errors->first('unauthenticated') }}</span>
                                </p>
                            @endif


                        </div>
                        <div class="footer text-center">
                            <button type="submit" class="btn btn-neutral btn-round btn-lg">Get Started</button>
                        </div>

                        <div class="col text-center">
                            <p>
                                <a href="{{ route('password.request') }}" class="link">Forgot Your Password?</a>
                            </p>
                        </div>

                        {!! app('captcha')->render(); !!}

                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection