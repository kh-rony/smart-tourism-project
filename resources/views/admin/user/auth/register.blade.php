@extends('admin.user.auth.layouts.app')

@section('title', 'Register')

@section('auth-content')

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.register') }}" method="POST">

            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group has-feedback">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Full name" value="{{ old('name') }}" required autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>

                @if ($errors->has('name'))
                    <br><span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <div class="row">
                    <label class="col-form-label col-sm-2 pt-0">Gender:</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender"
                                           id="gender" value="0"
                                           @if (old('gender') && old('gender') === 0) checked @endif>
                                    <label for="gender"> Male </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender"
                                           id="gender" value="1"
                                           @if (old('gender') && old('gender') === 1) checked @endif>
                                    <label for="gender"> Female </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->has('gender'))
                    <br><span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('gender') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="phone" type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>

                @if ($errors->has('phone'))
                    <br><span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                @if ($errors->has('password'))
                    <br><span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Retype password" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>

            <div class="row">
                {{--<div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> I agree to the <a href="#">terms</a>
                        </label>
                    </div>
                </div>--}}
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
                <!-- /.col -->
            </div>

            {!! app('captcha')->render(); !!}

        </form>
    </div>

@endsection