@extends('admin.layouts.app')

@section('headerIncludes')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

@endsection

@section('content-wrapper')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

    @include('admin.layouts.contentHeader')

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Hotels</h3>

                    @can('hotel-admin', Auth::user())
                        <a href="{{ route('hotel.create') }}" class="col-md-offset-5 btn btn-success">Add New</a>
                    @endcan

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">

                    @can('super-admin', Auth::user())

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Unpublish Hotels
                                    @if(!$unpublishHotels->count())
                                        : No Unpublish Hotels
                                    @endif
                                </h3>

                            </div>
                            <!-- /.box-header -->

                            @if($unpublishHotels->count())

                                <div class="box-body">

                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Name</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Publish Now</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($unpublishHotels as $hotel)

                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td><a href="{{ $hotel->slug }}">{{ $hotel->name }}</a></td>
                                                <td>{{ $hotel->city }}</td>
                                                <td>{{ $hotel->state }}</td>
                                                <td>{{ $hotel->country }}</td>
                                                <td>
                                                    <form id="publish-form-{{ $hotel->slug }}" action="{{ route('hotel.publish', $hotel->slug) }}" method="POST" style="display: none">

                                                        {{csrf_field()}}


                                                    </form>

                                                    <a href="{{ route('hotel.publish', $hotel->slug) }}" onclick="
                                                            if (confirm('Are you sure to publish?')) {
                                                            event.preventDefault();
                                                            document.getElementById('publish-form-{{ $hotel->slug }}').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            " class="btn btn-success"> <span class="fa fa-fw fa-check-square-o"></span>
                                                    </a>
                                                </td>
                                                <td><a href="{{ route('hotel.edit', $hotel->slug) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                                <td>
                                                    <form id="delete-form-{{ $hotel->slug }}" action="{{ route('hotel.destroy', $hotel->slug) }}" method="POST" style="display: none">

                                                        {{csrf_field()}}
                                                        {{method_field('DELETE')}}

                                                    </form>

                                                    <a href="{{ route('hotel.destroy', $hotel->slug) }}" onclick="
                                                            if (confirm('Are you sure to delete?')) {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $hotel->slug }}').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            " class="btn btn-danger"> <span class="glyphicon glyphicon-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>

                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Name</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Publish Now</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.box-body -->

                            @endif

                        </div>

                    @endcan

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Publish Hotels
                                @if(!$hotels->count())
                                    : No Published Hotels
                                @endif
                            </h3>
                        </div>
                        <!-- /.box-header -->

                        @if($hotels->count())

                            <div class="box-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Published</th>
                                        <th>Edit</th>
                                        @can('super-admin', Auth::user())
                                            <th>Delete</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($hotels as $hotel)

                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><a href="{{ $hotel->slug }}">{{ $hotel->name }}</a></td>
                                            <td>{{ $hotel->city }}</td>
                                            <td>{{ $hotel->state }}</td>
                                            <td>{{ $hotel->country }}</td>
                                            <td>@if($hotel->published){{'Yes'}}@else{{'No'}}@endif</td>
                                            <td><a href="{{ route('hotel.edit', $hotel->slug) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                            @can('super-admin', Auth::user())
                                            <td>
                                                <form id="delete-form-{{ $hotel->slug }}" action="{{ route('hotel.destroy', $hotel->slug) }}" method="POST" style="display: none">

                                                    {{csrf_field()}}
                                                    {{method_field('DELETE')}}

                                                </form>

                                                <a href="{{ route('hotel.destroy', $hotel->slug) }}" onclick="
                                                        if (confirm('Are you sure to delete?')) {
                                                        event.preventDefault();
                                                        document.getElementById('delete-form-{{ $hotel->slug }}').submit();
                                                        } else {
                                                        event.preventDefault();
                                                        }
                                                        " class="btn btn-danger"> <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                            @endcan
                                        </tr>

                                    @endforeach

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Published</th>
                                        <th>Edit</th>
                                        @can('super-admin', Auth::user())
                                            <th>Delete</th>
                                        @endcan
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>

                    @endif
                    <!-- /.box-body -->
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">

                </div>

                <!-- /.box-footer-->
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>

@endsection

@section('footerIncludes')

    <!-- DataTables -->
    <script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function () {
            $('#example1, #example2').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false
            })
        })
    </script>

@endsection