<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ERS DANIŞMANLIK</title>

    <!-- Admin Temaya Ait -->
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/font-awesome/css/font-awesome.css') }}">
    <!-- Admin Temaya Ait -->

    <!-- Sayfaya özel css -->
    <style>
        .cnavigation>nav{margin:0 0 13px 7px; float:left;}
        .paginetion-prev{
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-image: url(../frontend/assets/images/pagination-prev.svg);
            width: 20px;
            height: 20px;
            display: inline-block;
            content: ' ';
        }
        .paginetion-next{
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-image: url(../frontend/assets/images/pagination-next.svg);
            width: 20px;
            height: 20px;
            display: inline-block;
            content: ' ';
        }
    </style>
    @yield('custom_css')
    <!-- Sayfaya özel css -->

</head>
<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

    <div class="wrapper">
        <div id="loader"></div>

        @include('layouts.inc.header')
        @include('layouts.inc.aside')

        <div class="content-wrapper">
            <div class="container-full">
                @yield('content')
            </div>
        </div>

        @include('layouts.inc.footer')

    </div>

    <!-- Admin Temaya Ait -->
    <script src="{{ asset('backend/js/vendors.min.js') }}"></script>
    <script src="{{ asset('backend/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/js/template.js') }}"></script>
    <script src="{{ asset('backend/js/pages/dashboard3.js') }}"></script>
    <!-- Admin Temaya Ait -->

    @yield('custom_js')

</body>
</html>












