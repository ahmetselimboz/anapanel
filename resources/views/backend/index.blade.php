@extends('backend.layout')

@section('custom_css')
    <style>
        .card-custom {
            min-width: 120px;
            min-height: 150px;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: #00b8d4;
            text-decoration: none;
            color: black;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }


        .icon {
            font-size: 24px;
            margin-bottom: 10px;
            width: 60px;
            height: 60px;
            background: #8e8e8e;
            color: #2d2d2d;
            transition: 0.3s;
        }

        .card-custom:hover .icon {
            background: white;
            color: #00b8d4;
        }

        .card-container {
            gap: 10px;
        }

        .card-text {
            color: #8e8e8e !important;
        }

        .card-text {
            color: white !important;
        }
    </style>
@endsection

@section('content')

@endsection


@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
    </script>

    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>


@endsection
