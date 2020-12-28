<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">

	<title>Главная</title>

	<meta name="description" content="Главная">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link type="image/x-icon" href="/favicon.ico" rel="shortcut icon">

	<link rel="stylesheet" href="/css/awesome.css">
	<link rel="stylesheet" href="/css/animate.min.css">

	<link href="{{ mix('/css/app.css') }}" rel="stylesheet">
	<script src="{{ mix('/js/app.js') }}" defer></script>
</head>
<body>

	<div id="app"></div>

</body>
</html>
