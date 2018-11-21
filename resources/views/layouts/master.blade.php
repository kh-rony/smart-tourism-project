<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href={{ asset('img/apple-icon.png') }}>
    <link rel="icon" type="image/png" href={{ asset('img/favicon.png') }}>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Travel Mate</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href={{ asset('css/bootstrap.min.css') }} rel="stylesheet" />
    <link href={{ asset('css/now-ui-kit.css?v=1.1.0') }} rel="stylesheet" />

    <script src={{ asset('js/core/jquery.3.2.1.min.js') }} type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

</head>

<body class="index-page sidebar-collapse">

@include('layouts.nav')

@if (session('message'))

    <div class="alert alert-success" id="flash-message"  role="alert">

        {{session('message')}}

    </div>
@endif

@include('layouts.errors')
@yield('content')


@include('layouts.footer')

</body>
<!--   Core JS Files   -->
<script src={{ asset('js/core/popper.min.js') }} type="text/javascript"></script>
<script src={{ asset('js/core/bootstrap.min.js') }} type="text/javascript"></script>


</html>