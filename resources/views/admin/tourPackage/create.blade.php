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
                            <h3 class="box-title">Tour Package Create</h3>
                        </div>

                        <form role="form" method="POST" action="{{ route('tour-package.store') }}"
                              enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="box-body">

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="name">Tour Package Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                               placeholder="tour package name" value="{{ old('name') }}">
                                    </div>


                                    <div class="form-group">
                                        <label for="address1">Duration</label>
                                        <input type="text" class="form-control" name="duration" id="duration"
                                               placeholder="duration" value="{{ old('duration') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address2">Origin</label>
                                        <input type="text" class="form-control" name="origin" id="origin"
                                               placeholder="origin from" value="{{ old('origin') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address3">Destination</label>
                                        <input type="text" class="form-control" name="destination" id="destination"
                                               placeholder="destination" value="{{ old('destination') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address3">Transport</label>
                                        <input type="text" class="form-control" name="transport" id="transport"
                                               placeholder="transport" value="{{ old('transport') }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tax">Tax</label>
                                                <select class="form-control"
                                                        data-placeholder="Select" name="tax" id="tax">
                                                    <option selected disabled> Select</option>
                                                    <option value="yes">
                                                        Yes
                                                    </option>
                                                    <option value="no">
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="guide_service">Guide Service</label>
                                                <select class="form-control"
                                                        data-placeholder="Select" name="guide_service"
                                                        id="guide_service">
                                                    <option selected disabled> Select</option>
                                                    <option value="yes">
                                                        Yes
                                                    </option>
                                                    <option value="no">
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input type="number" class="form-control" name="price" id="price"
                                                       placeholder="price" value="{{ old('price') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" name="images[]" id="image" multiple="multiple"
                                                       style="margin-top: 5px">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="accommodation">Accommodation</label>
                                        <input type="text" class="form-control" name="accommodation" id="accommodation"
                                               placeholder="accommodation" value="{{ old('accommodation') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="breakfast">Breakfast</label>
                                        <input type="text" class="form-control" name="breakfast" id="breakfast"
                                               placeholder="breakfast" value="{{ old('breakfast') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="lunch">Lunch</label>
                                        <input type="text" class="form-control" name="lunch" id="lunch"
                                               placeholder="lunch" value="{{ old('lunch') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="dinner">Dinner</label>
                                        <input type="text" class="form-control" name="dinner" id="dinner"
                                               placeholder="dinner" value="{{ old('dinner') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="sight_seeing">Sight Seeing</label>
                                        <input type="text" class="form-control" name="sight_seeing" id="sight_seeing"
                                               placeholder="sight_seeing" value="{{ old('sight_seeing') }}">
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

                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="plans">Day wise plans</label>
                                        <div id="plansDiv"></div>
                                        <button type="button" class="btn btn-info btn-block btn-sm" id="plansBtn" title="Add Plan"><i class="fa fa-fw fa-plus"></i></button>
                                    </div>

                                </div>

                            </div>

                            <div class="box box-info">

                                <div class="box-header">
                                    <h3 class="box-title">Write Tour Package Description Here</h3>
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

                                    <textarea id="editor1" name="description" rows="8"
                                              cols="80">{{ old('description') }}</textarea>

                                </div>

                            </div>


                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('tour-package.index') }}"
                                   class="col-sm-offset-1 btn btn-warning">Back</a>
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

    <script>
        $('#plansBtn').click(function() {

            var blank;

            $('input[name^="plans"]').each( function() {
                if (this.value === '') {
                    blank = 1;
                    var div = $(this).parent();
                    div = div.parent();
                    div.remove();
                }

            });

            if (!blank) {
                $('#plansDiv').append(
                    '<div class="form-group">' +
                    '<div class="input-group">' +
                    '<input type="text" class="form-control" name="plans[]" placeholder="plan">' +
                    '<div class="input-group-addon top_amenities_addon">' +
                    '<i class="fa fa-fw fa-close"></i>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        });
    </script>

@endsection