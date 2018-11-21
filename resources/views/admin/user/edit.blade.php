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
                            <h3 class="box-title">Admin Edit</h3>
                        </div>

                            <div class="box-body">

                                <div class="col-md-offset-3 col-md-6">

                                    <form role="form" method="POST" action="{{ route('admin.update', $admin->id) }}">

                                        {{ csrf_field() }}

                                        {{ method_field('PATCH') }}

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $admin->name }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" value="{{ $admin->email }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                            <input type="checkbox" name="status" @if (old('status') || $admin->status) checked @endif value="1"> Status
                                            </label>
                                        </div>


                                        <div class="form-group">
                                            <label for="role">Admin Role</label>
                                            <select class="form-control" name="role" id="role">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->slug }}" @if($admin->role->slug == $role->slug) selected @endif>
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