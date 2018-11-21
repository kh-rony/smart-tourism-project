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

                        <form role="form" method="POST" action="{{ route('tour-package.update', $tourPackage->slug) }}"
                              enctype="multipart/form-data">

                            {{ csrf_field() }}

                            {{ method_field('PATCH') }}

                            <div class="box-body">

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="name">Tour Package Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                               placeholder="tour package name" value="@if(old('name')){{ old('name') }}@else{{ $tourPackage->name }}@endif">
                                    </div>


                                    <div class="form-group">
                                        <label for="address1">Duration</label>
                                        <input type="text" class="form-control" name="duration" id="duration"
                                               placeholder="duration" value="@if(old('duration')){{ old('duration') }}@else{{ $tourPackage->duration }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="address2">Origin</label>
                                        <input type="text" class="form-control" name="origin" id="origin"
                                               placeholder="origin from" value="@if(old('origin')){{ old('origin') }}@else{{ $tourPackage->origin }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="address3">Destination</label>
                                        <input type="text" class="form-control" name="destination" id="destination"
                                               placeholder="destination" value="@if(old('destination')){{ old('destination') }}@else{{ $tourPackage->destination }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="address3">Transport</label>
                                        <input type="text" class="form-control" name="transport" id="transport"
                                               placeholder="transport" value="@if(old('transport')){{ old('transport') }}@else{{ $tourPackage->transport }}@endif">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tax">Tax</label>
                                                <select class="form-control"
                                                        data-placeholder="Select" name="tax" id="tax">
                                                    <option selected disabled> Select</option>
                                                    <option value="yes" @if ($tourPackage->tax === 'yes') selected @endif>
                                                        Yes
                                                    </option>
                                                    <option value="no" @if ($tourPackage->tax === 'no') selected @endif>
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
                                                    <option value="yes" @if ($tourPackage->guide_service === 'yes') selected @endif>
                                                        Yes
                                                    </option>
                                                    <option value="no" @if ($tourPackage->guide_service === 'no') selected @endif>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" name="price" id="price"
                                               placeholder="price" value="@if(old('price')){{ old('price') }}@else{{ $tourPackage->price }}@endif">
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="accommodation">Accommodation</label>
                                        <input type="text" class="form-control" name="accommodation" id="accommodation"
                                               placeholder="accommodation" value="@if(old('accommodation')){{ old('accommodation') }}@else{{ $tourPackage->accommodation }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="breakfast">Breakfast</label>
                                        <input type="text" class="form-control" name="breakfast" id="breakfast"
                                               placeholder="breakfast" value="@if(old('breakfast')){{ old('breakfast') }}@else{{ $tourPackage->breakfast }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="lunch">Lunch</label>
                                        <input type="text" class="form-control" name="lunch" id="lunch"
                                               placeholder="lunch" value="@if(old('lunch')){{ old('lunch') }}@else{{ $tourPackage->lunch }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="dinner">Dinner</label>
                                        <input type="text" class="form-control" name="dinner" id="dinner"
                                               placeholder="dinner" value="@if(old('dinner')){{ old('dinner') }}@else{{ $tourPackage->dinner }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="sight_seeing">Sight Seeing</label>
                                        <input type="text" class="form-control" name="sight_seeing" id="sight_seeing"
                                               placeholder="sight_seeing" value="@if(old('sight_seeing')){{ old('sight_seeing') }}@else{{ $tourPackage->sight_seeing }}@endif">
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Select Tag</label>
                                        <select class="form-control select2" multiple="multiple"
                                                data-placeholder="Select Tag" name="tags[]" id="tags">
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->slug }}"
                                                        @foreach($tourPackage->tags as $packageTag)
                                                        @if ($packageTag->slug == $tag->slug)
                                                        selected
                                                        @endif
                                                        @endforeach
                                                >
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
                                                <option value="{{ $category->slug }}"
                                                        @foreach($tourPackage->categories as $packageCategory)
                                                        @if ($packageCategory->slug == $category->slug)
                                                        selected
                                                        @endif
                                                        @endforeach
                                                >
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="plans">Daywise plans</label>
                                        <div id="plansDiv">
                                            @foreach($tourPackage->plans as $plan)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="plans[]" placeholder="plan" value="{{ $plan }}">
                                                        <div class="input-group-addon top_amenities_addon">
                                                            <i class="fa fa-fw fa-close"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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
                                              cols="80">@if(old('description')){{ old('description') }}@else{{ $tourPackage->description->body }}@endif</textarea>

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