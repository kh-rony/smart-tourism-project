@extends('layouts.master')

@section('content')

    <div class="col-sm-8">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2 class="text-center">Registration Confirmed</h2></div>
                <div class="panel-body">
                    <h5 class="text-center">Your Email is successfully verified. Click here to <a href="{{ route('session.create') }}">login</a></h5>
                </div>
            </div>
        </div>
    </div>
    </div>


@endsection