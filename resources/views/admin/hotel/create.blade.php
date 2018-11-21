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
                            <h3 class="box-title">Hotel Create</h3>
                        </div>

                        <form role="form" method="POST" action="{{ route('hotel.store') }}" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="box-body">

                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="name">Hotel Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="hotel name" value="{{ old('name') }}">
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="email" value="{{ old('email') }}">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="website">Website</label>
                                                <input type="text" class="form-control" name="website" id="website" placeholder="website" value="{{ old('website') }}">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="number" class="form-control" name="phone" id="phone" placeholder="phone" value="{{ old('phone') }}">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

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

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="address2">Address2</label>
                                                <input type="text" class="form-control" name="address2" id="address2" placeholder="address (sub-district)" value="{{ old('address2') }}">
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="address3">Address3</label>
                                                <input type="text" class="form-control" name="address3" id="address3" placeholder="address (district)" value="{{ old('address3') }}">
                                            </div>

                                        </div>

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
                                                <label for="total_rooms">Total Rooms</label>
                                                <input type="number" class="form-control" min="1" step="1" name="total_rooms" id="total_rooms" placeholder="total rooms" value="{{ old('total_rooms') }}">
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
                                                <label for="type">Hotel Type</label>
                                                <select class="form-control" name="type" id="type">
                                                    <option selected disabled> Select</option>
                                                    @foreach($types as $type)
                                                        <option value="{{ $type->slug }}">
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="rooms">Room Types</label>
                                        <select class="form-control select2" multiple="multiple" data-placeholder="Select Room Types"
                                                style="width: 100%;" name="rooms[]" id="rooms">
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->slug }}">
                                                    {{ $room->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                            </div>

                            <div class="box-body">

                                <div class="row" style="margin: 0 4px">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="topAmenitiesBtn">Top Amenities</label>
                                        <div id="topAmenitiesDiv"></div>
                                        <button type="button" class="btn btn-info btn-block btn-sm" id="topAmenitiesBtn" title="Add Top Amenities"><i class="fa fa-fw fa-plus"></i></button>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="hotelAmenitiesBtn">Hotel Amenities</label>
                                        <div id="hotelAmenitiesDiv"></div>
                                        <button type="button" class="btn btn-info btn-block btn-sm" id="hotelAmenitiesBtn" title="Add Hotel Amenities"><i class="fa fa-fw fa-plus"></i></button>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="roomAmenitiesBtn">Room Amenities</label>
                                        <div id="roomAmenitiesDiv"></div>
                                        <button type="button" class="btn btn-info btn-block btn-sm" id="roomAmenitiesBtn" title="Add Room Amenities"><i class="fa fa-fw fa-plus"></i></button>
                                    </div>

                                </div>
                                </div>
                            </div>

                            <div class="box box-info">

                                <div class="box-header">
                                    <h3 class="box-title">Write Hotel Description Here</h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body pad" style="">

                                    <textarea id="editor1" name="body" rows="10" cols="80">{{ old('body') }}</textarea>

                                </div>

                            </div>


                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('hotel.index') }}" class="col-sm-offset-1 btn btn-warning">Back</a>
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
    <!-- Page script -->
    <script>

        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1')
            //Initialize Select2 Elements
            $('.select2').select2()
        });

        $('#topAmenitiesBtn').click(function() {

            var blank;

            $('input[name^="top_amenities"]').each( function() {
                if (this.value === '') {
                    blank = 1;
                    var div = $(this).parent();
                    div = div.parent();
                    div.remove();
                }

            });

            if (!blank) {
                $('#topAmenitiesDiv').append(
                    '<div class="form-group">' +
                        '<div class="input-group">' +
                            '<input type="text" class="form-control" name="top_amenities[]" placeholder="name">' +
                            '<div class="input-group-addon top_amenities_addon">' +
                                '<i class="fa fa-fw fa-close"></i>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );
            }
        });

        $('#hotelAmenitiesBtn').click(function() {

            var blank;

            $('input[name^="hotel_amenities"]').each( function() {
                if (this.value === '') {
                    blank = 1;
                    var div = $(this).parent();
                    div = div.parent();
                    div.remove();
                }

            });

            if (!blank) {
                $('#hotelAmenitiesDiv').append(
                    '<div class="form-group">' +
                    '<div class="input-group">' +
                    '<input type="text" class="form-control" name="hotel_amenities[]" placeholder="name">' +
                    '<div class="input-group-addon hotel_amenities_addon">' +
                    '<i class="fa fa-fw fa-close"></i>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        });

        $('#roomAmenitiesBtn').click(function() {

            var blank;

            $('input[name^="room_amenities"]').each( function() {
                if (this.value === '') {
                    blank = 1;
                    var div = $(this).parent();
                    div = div.parent();
                    div.remove();
                }

            });

            if (!blank) {
                $('#roomAmenitiesDiv').append(
                    '<div class="form-group">' +
                    '<div class="input-group">' +
                    '<input type="text" class="form-control" name="room_amenities[]" placeholder="name">' +
                    '<div class="input-group-addon room_amenities_addon">' +
                    '<i class="fa fa-fw fa-close"></i>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        });


        $(document).on('click', 'div.top_amenities_addon, div.hotel_amenities_addon, div.room_amenities_addon', function () {
            var div = $(this).parent();
            div = div.parent();
            div.remove();
        });



    </script>

@endsection