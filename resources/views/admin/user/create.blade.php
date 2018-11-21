@extends('admin.layouts.app')

@section('headerIncludes')


@endsection

@section('content-wrapper')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

    @include('admin.layouts.contentHeader')

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Admin Create</h3>
                        </div>

                            <div class="box-body">

                                <div class="col-md-offset-3 col-md-6">

                                    <form role="form" method="POST" action="{{ route('admin.store') }}">

                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="email" value="{{ old('email') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Admin Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option disabled selected>Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->slug }}">
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.index') }}" class="col-sm-offset-1 btn btn-warning">Back</a>
                                        </div>

                                    </form>

                                </div>

                            </div>
                    </div>

                    @include('errors')

                </div>
                <!-- /.col-->
            </div>
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@section('footerIncludes')


@endsection