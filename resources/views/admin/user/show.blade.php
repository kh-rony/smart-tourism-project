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
                    <h3 class="box-title">Admins</h3>

                    <a href="{{ route('admin.create') }}" class="col-md-offset-5 btn btn-success">Add New</a>

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
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Assigned Role</th>
                                    <th class="text-center">Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($admins as $admin)

                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td class="text-center">{{ $admin->email }}</td>
                                        <td>@if($admin->status){{'Active'}}@else{{'Inactive'}}@endif</td>
                                        <td>{{$admin->role->name}}</td>
                                        <td><a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                        <td>
                                            <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.destroy', $admin->id) }}" method="POST" style="display: none">

                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}

                                            </form>

                                            <a href="{{ route('admin.destroy', $admin->id) }}" onclick="
                                                    if (confirm('Are you sure to delete?')) {
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{ $admin->id }}').submit();
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
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Assigned Roles</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
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