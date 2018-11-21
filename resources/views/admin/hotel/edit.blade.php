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
                            <h3 class="box-title">Hotel Edit</h3>
                        </div>

                        <form role="form" method="POST" action="{{ route('hotel.update', $hotel->slug) }}" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            {{ method_field('PATCH') }}

                            <div class="box-body">

                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="name">Hotel Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="hotel name" value="@if(old('name')){{ old('name') }}@else{{ $hotel->name }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="email" value="@if(old('email')){{ old('email') }}@else{{ $hotel->email }}@endif">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="website">Website</label>
                                                <input type="text" class="form-control" name="website" id="website" placeholder="website" value="@if(old('website')){{ old('website') }}@else{{ $hotel->website }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="number" class="form-control" name="phone" id="phone" placeholder="phone" value="@if(old('phone')){{ old('phone') }}@else{{ $hotel->phone }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="zip_code">Zip Code</label>
                                                <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="zip code" value="@if(old('zip_code')){{ old('zip_code') }}@else{{ $hotel->zip_code }}@endif">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address1">Address1</label>
                                        <input type="text" class="form-control" name="address1" id="address1" placeholder="address" value="@if(old('address1')){{ old('address1') }}@else{{ $hotel->address1 }}@endif">
                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="address2">Address2</label>
                                                <input type="text" class="form-control" name="address2" id="address2" placeholder="address (sub-district)" value="@if(old('address2')){{ old('address2') }}@else{{ $hotel->address2 }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="address3">Address3</label>
                                                <input type="text" class="form-control" name="address3" id="address3" placeholder="address (district)" value="@if(old('address3')){{ old('address3') }}@else{{ $hotel->address3 }}@endif">
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" name="city" id="city" placeholder="city" value="@if(old('city')){{ old('city') }}@else{{ $hotel->city }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <input type="text" class="form-control" name="state" id="state" placeholder="state" value="@if(old('state')){{ old('state') }}@else{{ $hotel->state }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <input type="text" class="form-control" name="country" id="country" placeholder="country" value="@if(old('country')){{ old('country') }}@else{{ $hotel->country }}@endif">
                                            </div>


                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="latitude" value="@if(old('latitude')){{ old('latitude') }}@else{{ $hotel->latitude }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="longitude" value="@if(old('longitude')){{ old('longitude') }}@else{{ $hotel->longitude }}@endif">
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="total_rooms">Total Rooms</label>
                                                <input type="number" class="form-control" min="1" step="1" name="total_rooms" id="total_rooms" placeholder="total rooms" value="@if(old('total_rooms')){{ old('total_rooms') }}@else{{ $hotel->total_rooms }}@endif">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        {{--<div class="col-md-4">

                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" name="img_url[]" id="image" multiple="multiple" style="margin-top: 5px">
                                            </div>

                                        </div>--}}
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="type">Hotel Type</label>
                                                <select class="form-control" name="type" id="type">
                                                    <option selected disabled> Select</option>
                                                    @foreach($types as $type)
                                                        <option value="{{ $type->slug }}" @if($hotel->type->slug === $type->slug) selected @endif>
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
                                                <option value="{{ $room->slug }}"
                                                        @foreach($hotel->rooms as $hotelRoom)
                                                        @if ($hotelRoom->slug === $room->slug)
                                                        selected
                                                        @endif
                                                        @endforeach>
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
                                            <div id="topAmenitiesDiv">

                                                @foreach($topAmenities->amenities as $amenity)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="top_amenities[]" placeholder="name" value="{{ $amenity->name }}">
                                                        <div class="input-group-addon top_amenities_addon">
                                                            <i class="fa fa-fw fa-close"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach

                                            </div>
                                            <button type="button" class="btn btn-info btn-block btn-sm" id="topAmenitiesBtn" title="Add Top Amenities"><i class="fa fa-fw fa-plus"></i></button>
                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="hotelAmenitiesBtn">Hotel Amenities</label>
                                            <div id="hotelAmenitiesDiv">

                                                @foreach($hotelAmenities->amenities as $amenity)
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="hotel_amenities[]" placeholder="name" value="{{ $amenity->name }}">
                                                            <div class="input-group-addon hotel_amenities_addon">
                                                                <i class="fa fa-fw fa-close"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <button type="button" class="btn btn-info btn-block btn-sm" id="hotelAmenitiesBtn" title="Add Hotel Amenities"><i class="fa fa-fw fa-plus"></i></button>
                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="roomAmenitiesBtn">Room Amenities</label>
                                            <div id="roomAmenitiesDiv">

                                                @foreach($roomAmenities->amenities as $amenity)
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="room_amenities[]" placeholder="name" value="{{ $amenity->name }}">
                                                            <div class="input-group-addon room_amenities_addon">
                                                                <i class="fa fa-fw fa-close"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
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

                                    <textarea id="editor1" name="body" rows="10" cols="80">@if(old('body')){{ old('body') }}@else{{ $hotel->description->body }}@endif</textarea>

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