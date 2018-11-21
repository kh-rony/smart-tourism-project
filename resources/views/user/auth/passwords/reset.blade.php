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

                    @if (session('token'))
                        <div class="alert alert-danger">
                            {{ session('token') }}
                        </div>
                    @endif

                    <form class="form" method="POST" action="{{ route('password.request') }}">

                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="header text-center">
                            <h4 class="title title-up">Reset Password</h4>
                        </div>
                        <div class="card-body">

                            <div class="input-group form-group-no-border">
                                        <span class="input-group-addon">
                                            <i class="now-ui-icons ui-1_email-85"></i>
                                        </span>
                                <input type="email" class="form-control" name="email" placeholder="Email..."
                                       value="{{ old('email') }}" required>
                            </div>
                            @if ($errors->has('email'))
                                <p>
                                    <span>{{ $errors->first('email') }}</span>
                                </p>
                            @endif

                            <div class="input-group form-group-no-border">
                                        <span class="input-group-addon">
                                            <i class="now-ui-icons text_caps-small"></i>
                                        </span>
                                <input type="password" class="form-control" name="password" placeholder="Password..."
                                       required>
                            </div>
                            @if ($errors->has('password'))
                                <p>
                                    <span>{{ $errors->first('password') }}</span>
                                </p>
                            @endif

                            <div class="input-group form-group-no-border">
                                        <span class="input-group-addon">
                                            <i class="now-ui-icons text_caps-small"></i>
                                        </span>
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="Confirm Password..." required>
                            </div>
                            @if ($errors->has('password'))
                                <p>
                                    <span>{{ $errors->get('password')[1] }}</span>
                                </p>
                            @endif

                        </div>
                        <div class="footer text-center">
                            <button type="submit" class="btn btn-neutral btn-round btn-lg">Get Started</button>
                        </div>

                        {!! app('captcha')->render(); !!}

                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection
