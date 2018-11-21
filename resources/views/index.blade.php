<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Tourism</title>
    <link rel="shortcut icon" href="https://cdn.vuetifyjs.com/images/logos/v-alt.svg" >
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <script src="//cdn.ckeditor.com/4.9.1/full/ckeditor.js"></script>
    <script type="text/javascript">
        window.access_token = `{!! session('access_token') !!}`;
        window.verified = `{!! session('verified') !!}`;
        window.reset_token = `{!! session('reset_token') !!}`;
        window.msg = `{!! session('message') !!}`;
    </script>
</head>
<body>

<div id="app"></div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFGocyfqrLSeQWFusajEejdtYnbVtbFjs"></script>
</body>
</html>