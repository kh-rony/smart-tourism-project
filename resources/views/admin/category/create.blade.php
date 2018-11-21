@extends('admin.layouts.app')

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
                            <h3 class="box-title">Category Create</h3>
                        </div>

                        <div class="box-body">

                            <div class="col-md-offset-3 col-md-6">

                                <form role="form" method="POST" action="{{ route('category.store') }}">

                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="name">Category Name</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="name" value="{{ old('name') }}">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('category.index') }}" class="col-sm-offset-1 btn btn-warning">Back</a>
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
