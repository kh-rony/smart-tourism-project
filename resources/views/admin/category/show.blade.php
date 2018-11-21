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
                    <h3 class="box-title">Categories</h3>

                    @if (Gate::check('place-admin') || Gate::check('article-admin') || Gate::check('travel-agent'))
                    <a href="{{ route('category.create') }}" class="col-md-offset-5 btn btn-success">Add New</a>
                    @endif

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Data Table With Full Features</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Name</th>
                                    <th>Edit</th>
                                    @can('super-admin', Auth::user())
                                    <th>Delete</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($categories as $category)

                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td><a href="{{ route('category.edit', $category->slug) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                        @can('super-admin', Auth::user())
                                        <td>
                                            <form id="delete-form-{{ $category->slug }}" action="{{ route('category.destroy', $category->slug) }}" method="POST" style="display: none">

                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}

                                            </form>

                                            <a href="{{ route('category.destroy', $category->slug) }}" onclick="
                                                    if (confirm('Are you sure to delete?')) {
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{ $category->slug }}').submit();
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
                                    <th>Edit</th>
                                    @can('super-admin', Auth::user())
                                    <th>Delete</th>
                                    @endcan
                                </tr>
                                </tfoot>
                            </table>
                        </div>
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
            $('#example1').DataTable({
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