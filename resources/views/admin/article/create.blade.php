@extends('admin.layouts.app')

@section('headerIncludes')

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/select2/dist/css/select2.min.css') }}">


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
                            <h3 class="box-title">Article Create</h3>
                        </div>

                        <form role="form" method="POST" action="{{ route('article.store') }}"
                              enctype="multipart/form-data">

                            <div class="box-body">

                                <div class="col-md-offset-3 col-md-6">


                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               placeholder="article title" value="{{ old('title') }}">
                                    </div>


                                    <div class="form-group">
                                        <label for="categories">Select Category</label>
                                        <select class="form-control select2" multiple="multiple"
                                                data-placeholder="Select Category" name="categories[]" id="categories">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->slug }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Select Tag</label>
                                        <select class="form-control select2" multiple="multiple"
                                                data-placeholder="Select Tag" name="tags[]" id="tags">
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->slug }}">
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" class="form-control" name="images[]" id="image"
                                               multiple="multiple">
                                    </div>


                                </div>

                            </div>

                            <div class="box box-info">

                                <div class="box-header">
                                    <h3 class="box-title">Write Place Description Here</h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse"
                                                data-toggle="tooltip" title="" data-original-title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body pad">

                                    <textarea id="editor1" name="content" rows="8"
                                              cols="80">{{ old('content') }}</textarea>

                                </div>

                            </div>


                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('article.index') }}" class="col-sm-offset-1 btn btn-warning">Back</a>
                            </div>
                        </form>

                        </div>

                    </div>

                    @include('errors')

                <!-- /.col-->
            </div>
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@section('footerIncludes')

    <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>">
    <!-- Select2 -->
    <script src="{{ asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page script -->
    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1')
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>

@endsection