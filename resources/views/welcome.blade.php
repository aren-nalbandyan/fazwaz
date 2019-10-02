<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 5.6 Vue JS axios post - ItSolutionStuff.com</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<h1>laravel </h1>
<div id="app">
    <dashboard-component  status_arr="{{$statuses}}" country_arr="{{ $countries }}" property_type="{{ $types }}" properties="{{$properties}}"></dashboard-component>
</div>
<script src="{{asset('js/app.js')}}" ></script>
</body>
</html>
