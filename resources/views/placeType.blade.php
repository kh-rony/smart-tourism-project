@if (count($venues))
<div class='container my-3 my-sm-5'>

    {{--<h1 class='text-center text-success mb-sm-4'>{{$data->category}}</h1>--}}
    <div class='row'>

        @foreach($venues as $venue)

            <div class='col-12 col-md-4 col-lg-3 my-3 my-sm-5 bg-faded'>
                <a href=""><h5 class='text-info'>{{$venue->name}}</h5></a>

                <a href="">
                    <figure class='figure' style="width: 200px; height: 200px">
                        <img class='figure-img img-thumbnail' alt='Figure image' width='200px' height='200px'
                             style="width: 100%; height: 100%"
                             src="{{$venue->thumbnail_url}}">
                    </figure>
                </a>
                <p>
                    Rating: {{$venue->rating}}&nbsp;
                    Location: {{$venue->location->lat}}, {{$venue->location->lng}}<br>

                </p>
            </div>



        @endforeach


    </div>


</div>
@endif