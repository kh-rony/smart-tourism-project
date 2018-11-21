@extends('layouts.master')

@section('content')

    <div class="col-sm-8 mx-auto">
        <div class="row">
            <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2 class="text-center">Registration Confirmed</h2></div>
                <div class="panel-body">
                    <h5 class="text-center">Hello {{ $user }}, your Email is successfully verified. Click here to <a href="{{ route('login') }}">login</a></h5>
                </div>
            </div>
        </div>
    </div>
    </div>


@endsection