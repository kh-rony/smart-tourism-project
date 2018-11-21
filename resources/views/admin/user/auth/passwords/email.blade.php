@extends('admin.user.auth.layouts.app')

@section('title', 'Reset Password')

@section('auth-content')

    <div class="login-box-body">
        <p class="login-box-msg">Reset Password</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST">

            {{ csrf_field() }}

            <div class="form-group has-feedback">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                @if ($errors->has('email'))

                    <span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>

                @endif
            </div>

            <div class="row">
                <div class="col-xs-offset-2 col-xs-8">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Send Password Reset Link</button>
                </div>
            </div>

            {!! app('captcha')->render(); !!}

        </form>

    </div>

@endsection