@extends('layouts.master')

@section('content')

    <div class="wrapper">
        <div class="page-header clear-filter" filter-color="orange" style="height: 500px">
            <div class="page-header-image" data-parallax="true"
                 style="background-image: url({{ asset('img/header.jpg') }}); opacity: 0.7">
            </div>
            <div class="container">

                <div class="content-center brand">


                    <form class="form" id="searchTour" method="POST" action="">

                        <div class="row" style="margin: 20px">
                            <div class="col-12 col-md-9 mb-2 mb-md-0">
                                <input class="form-control autocomplete form-control-lg" placeholder="Enter your destination" type="text" name="destination" id="destination">
                            </div>
                            <div class="col-12 col-md-2">
                                <button style="margin: 0" type="submit" class="btn btn-primary btn-round" id="btnSearch">Search
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <div class="main">

            <div class="container" id="weather"></div>

            <div class="container" id="data"></div>

            <div class="text-center" id="loading" style="display: none">
                <img src="{{ asset('img/loading.gif') }}" width='50px' height='50px'>
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script>

        $(document).ready(function () {

            if ($.trim($("#destination").val())) {
                fetchData();
            }


            /** auto complete **/
            $("#destination").keyup(function () {

                var textField = $(this);

                var name = $(this).val();

                $.ajax({

                    type: 'POST',
                    url: 'autocomplete',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        name: name
                    },
                    success: function (data) {
                        var names = JSON.parse(data);

                        $(textField).autocomplete({
                            source: names
                        });
                    }

                });

            });


            /**destination submit **/
            $("form#searchTour").submit(function (event) {

                event.preventDefault();
                $("#data,#weather").html("");

                fetchData();
            });


            function fetchData() {

                var destination = $.trim($("#destination").val());

                $("#btnSearch").prop('disabled', true);
                $("#loading").show();
                weatherStatus(destination);
                placeResults(destination);

                $(document).ajaxStop(function () {
                    $("#btnSearch").prop('disabled', false);
                    $("#loading").hide();
                });

            }


            /** fetch place search result **/
            function placeResults(destination) {


                    var queryData = {
                        destination: destination
                    };

                    $.ajax({
                        type: 'GET',
                        url: 'placeResult',
                        data: {
                            //                                '_token': $('meta[name="csrf-token"]').attr('content'),
                            query: JSON.stringify(queryData)
                        },
                        success: function (data) {
                            $("#data").append(data);
                        }

                    });



            }


            /** fetch weather status **/
            function weatherStatus(destination) {

                //$("#loading").css('display', 'block');
                var queryData = {

                    destination: destination
                };

                $.ajax({
                    type: 'GET',
                    url: 'weatherStatus',
                    data: {

                        query: JSON.stringify(queryData)
                    },
                    success: function (data) {
                        //$("#loading").css('display', 'none');
                        $("#weather").html(JSON.parse(data));

                    }

                });
            }

            /** toogle for weather detail show **/
            $(document).on('click', 'img.showDetail', function () {

                if ($(this).attr('src') === "/img/glyphicons/open.png") {
                    $(this).attr('src', "/img/glyphicons/close.png");
                } else {
                    $(this).attr('src', "/img/glyphicons/open.png");
                }

                var d = $(this).parent();
                var dParent = $(d).parent();
                var dParentParent = $(dParent).parent();
                $(dParentParent).next("div").toggle("slow");

            });


        });

    </script>


@endsection