@extends('admin.layouts.app')

@section('headerIncludes')

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">


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
                                <h3 class="box-title">Place Create</h3>
                            </div>

                            <form role="form" method="POST" action="{{ route('place.store') }}" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="box-body">

                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="name">Place Name</label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="place name" value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="zip_code">Zip Code</label>
                                                    <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="zip code" value="{{ old('zip_code') }}">
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address1">Address1</label>
                                            <input type="text" class="form-control" name="address1" id="address1" placeholder="address" value="{{ old('address1') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="address2">Address2</label>
                                            <input type="text" class="form-control" name="address2" id="address2" placeholder="address (sub-district)" value="{{ old('address2') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="address3">Address3</label>
                                            <input type="text" class="form-control" name="address3" id="address3" placeholder="address (district)" value="{{ old('address3') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="categories">Select Category</label>
                                            <select class="form-control select2" multiple="multiple" data-placeholder="Select Category" name="categories[]" id="categories">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->slug }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>

                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" name="city" id="city" placeholder="city" value="{{ old('city') }}">
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <input type="text" class="form-control" name="state" id="state" placeholder="state" value="{{ old('state') }}">
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    <input type="text" class="form-control" name="country" id="country" placeholder="country" value="{{ old('country') }}">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="latitude">Latitude</label>
                                                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="latitude" value="{{ old('latitude') }}">
                                                </div>

                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="longitude">Longitude</label>
                                                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="longitude" value="{{ old('longitude') }}">
                                                </div>

                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="weekly_holiday">Weekly Holiday</label>
                                                    <select class="form-control" name="weekly_holiday" id="weekly_holiday">
                                                        <option selected disabled> Select weekly holiday</option>
                                                        @foreach($days as $day)
                                                            <option value="{{ $loop->index }}" @if (old('weekly_holiday') === $loop->index ) selected @endif>
                                                                {{ $day }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">

                                                <!-- time Picker -->
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="start_hour">Start Hour</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control timepicker" name="start_hour" id="start_hour" placeholder="start hour" value="{{ old('start_hour') }}">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">

                                                <!-- time Picker -->
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="end_hour">End Hour</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control timepicker" name="end_hour" id="end_hour" placeholder="end hour" value="{{ old('end_hour') }}">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="price">Price($)</label>
                                                    <input type="number" class="form-control" min="1" step="any" name="price" id="price" placeholder="price($)" value="{{ old('price') }}">
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="image">Image</label>
                                                    <input type="file" name="images[]" id="image" multiple="multiple" style="margin-top: 5px">
                                                </div>

                                            </div>

                                            <div class="col-md-8">

                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="text" class="form-control" name="website" id="website" placeholder="website" value="{{ old('website') }}">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="tags">Select Tag</label>
                                            <select class="form-control select2" multiple="multiple" data-placeholder="Select Tag" name="tags[]" id="tags">
                                                @foreach($tags as $tag)
                                                    <option value="{{ $tag->slug }}">
                                                        {{ $tag->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                </div>

                                <div class="box box-info">

                                    <div class="box-header">
                                        <h3 class="box-title">Write Place Description Here</h3>
                                        <!-- tools box -->
                                        <div class="pull-right box-tools">
                                            <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                                <i class="fa fa-minus"></i></button>
                                        </div>
                                        <!-- /. tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">

                                        <textarea id="editor1" name="body" rows="8" cols="80">{{ old('body') }}</textarea>

                                    </div>

                                </div>


                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('place.index') }}" class="col-sm-offset-1 btn btn-warning">Back</a>
                                </div>

                            </form>

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

    <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>">
    <!-- Select2 -->
    <script src="{{ asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <!-- Page script -->
    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1')
            //Initialize Select2 Elements
            $('.select2').select2()
            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false,
                defaultTime: false
            })
        })
    </script>

@endsection