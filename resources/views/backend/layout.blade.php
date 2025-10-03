<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset( "favicon.ico") }}" type="image/x-icon">
    <title>VMG MEDYA</title>

    <!-- Admin Temaya Ait -->
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/font-awesome/css/font-awesome.css') }}">
     <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

        @include('backend.layouth.header')
        @include('backend.layouth.aside')

        <div class="content-wrapper">
            <div class="container-full">
                @yield('content')
            </div>
        </div>


        @include('backend.layouth.footer')

    </div>

    <!-- Admin Temaya Ait -->
    <script src="{{ asset('backend/js/vendors.min.js') }}"></script>
    <script src="{{ asset('backend/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/js/template.js') }}"></script>
    <script src="{{ asset('backend/js/pages/dashboard3.js') }}"></script>
    <!-- Admin Temaya Ait -->
   <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            $('.input-mask-number').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Sadece rakamları kabul eder
            });

            // Laravel toastr flash messages handler
            @if (Session::has('toastr'))
                @php $toastr = Session::get('toastr'); @endphp
                toastr.{{ $toastr['type'] }}("{{ $toastr['message'] }}", "{{ $toastr['title'] ?? '' }}");
            @endif

            // Standard Laravel flash messages
            @if (Session::has('success'))
                toastr.success("{{ Session::get('success') }}", "Başarılı");
            @endif
            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}", "Hata");
            @endif
            @if (Session::has('warning'))
                toastr.warning("{{ Session::get('warning') }}", "Uyarı");
            @endif
            @if (Session::has('info'))
                toastr.info("{{ Session::get('info') }}", "Bilgi");
            @endif
        });
    </script>
    @yield('custom_js')


</body>
</html>












