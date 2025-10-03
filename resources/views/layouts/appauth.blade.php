<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'VMG MEDYA')</title>


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Admin Temaya Ait -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/font-awesome/css/font-awesome.css') }}">
   
    <!-- Admin Temaya Ait -->
</head>
<body class="hold-transition theme-primary bg-img" style="background-image: url({{ asset('backend/auth-bg/bg-1.jpg') }})">


@yield('content')

<!-- Vendor JS -->
<script src="{{ asset('backend/js/vendors.min.js') }}"></script>

</body>
</html>
