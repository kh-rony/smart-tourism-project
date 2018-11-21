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
                    <h3 class="box-title">Places</h3>

                    @can('article-admin', Auth::user())
                        <a href="{{ route('article.create') }}" class="col-md-offset-5 btn btn-success">Add New</a>
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
                                <h3 class="box-title">Unpublish Articles
                                    @if(!$unpublishArticles->count())
                                        : No Unpublish Articles
                                    @endif
                                </h3>

                            </div>
                            <!-- /.box-header -->

                            @if($unpublishArticles->count())

                                <div class="box-body">

                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Title</th>
                                            <th>Published</th>
                                            <th>Publish Now</th>
                                            <th>Edit</th>
                                            <th>Delete</th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($unpublishArticles as $article)

                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td><a href="{{ $article->slug }}">{{ $article->title }}</a></td>
                                                <td>@if($article->published){{'Yes'}}@else{{'No'}}@endif</td>
                                                <td>
                                                    <form id="publish-form-{{ $article->slug }}" action="{{ route('article.publish', $article->slug) }}" method="POST" style="display: none">

                                                        {{csrf_field()}}


                                                    </form>

                                                    <a href="{{ route('article.publish', $article->slug) }}" onclick="
                                                            if (confirm('Are you sure to publish?')) {
                                                            event.preventDefault();
                                                            document.getElementById('publish-form-{{ $article->slug }}').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            " class="btn btn-success"> <span class="fa fa-fw fa-check-square-o"></span>
                                                    </a>
                                                </td>
                                                <td><a href="{{ route('article.edit', $article->slug) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                                <td>
                                                    <form id="delete-form-{{ $article->slug }}" action="{{ route('article.destroy', $article->slug) }}" method="POST" style="display: none">

                                                        {{csrf_field()}}
                                                        {{method_field('DELETE')}}

                                                    </form>

                                                    <a href="{{ route('article.destroy', $article->slug) }}" onclick="
                                                            if (confirm('Are you sure to delete?')) {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $article->slug }}').submit();
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
                                            <th>Title</th>
                                            <th>Published</th>
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
                            <h3 class="box-title">Publish Articles
                                @if(!$articles->count())
                                    : No Published Articles
                                @endif
                            </h3>
                        </div>
                        <!-- /.box-header -->

                        @if($articles->count())

                            <div class="box-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Title</th>
                                        <th>Published</th>
                                        <th>Edit</th>
                                        @can('super-admin', Auth::user())
                                            <th>Delete</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($articles as $article)

                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><a href="{{ $article->slug }}">{{ $article->title }}</a></td>
                                            <td>@if($article->published){{'Yes'}}@else{{'No'}}@endif</td>
                                            <td><a href="{{ route('article.edit', $article->slug) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                                            @can('super-admin', Auth::user())
                                            <td>
                                                <form id="delete-form-{{ $article->slug }}" action="{{ route('article.destroy', $article->slug) }}" method="POST" style="display: none">

                                                    {{csrf_field()}}
                                                    {{method_field('DELETE')}}

                                                </form>

                                                <a href="{{ route('article.destroy', $article->slug) }}" onclick="
                                                        if (confirm('Are you sure to delete?')) {
                                                        event.preventDefault();
                                                        document.getElementById('delete-form-{{ $article->slug }}').submit();
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
                                        <th>Title</th>
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