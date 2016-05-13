<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="{{ $site_keywords }}" />
	<meta name="Description" content="{{ $site_description }}" />
	<title>{{ $site_title }}</title>
	<!-- Fonts -->
    <link href="{{ url('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ url('css/auto.css') }}" rel='stylesheet' type='text/css'>
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body class="login2">
	
    @yield('content')
	<!-- JavaScripts -->
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
	<script src="http://www.webshowu.com/themes/member/skin/modernizr.custom.js" type="text/javascript"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
