@extends($theme_path . '.frontend_layout')

@section('custom_css')
    <style>
        .header-news {
            background: #E52020;
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 2%;

        }
        .badge-custom {
            background-color: yellow;
            color: black;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 6px;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .search-section {
            background: linear-gradient(to right, #ffe742, #ff8d43);
            /* Sarıdan turuncuya geçiş */
            padding: 15px;
            border-radius: 5px;
        }

        .card-img, .card-img-top, .card-img-bottom {
                width: 100%;
                min-height: 666px;
            }


        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            .search-section {
                flex-direction: column;
                gap: 10px;
            /* }
            .card-img, .card-img-top, .card-img-bottom {
                width: 50%;
                min-height: auto;
            } */

        }
        .btn-group input[type="date"] {
            max-width: 200px;
            width: 200px;
        }
    </style>
@endsection

@section('content')



    <div class="container mb-4">
        <div class="row mb-3 header-news">
            <div class="col-12 col-md-8">
                <h1 class="text-capitalize">E-Gazete Arşivi</h1>
                <!-- <p class="">E-Gazete Arşivimizdeki tüm bültenleri tarih seçerek inceleyebilirsiniz.</p> -->
            </div>
            <div class="col-12 col-md-4">
                <div class="float-right d-flex align-items-baseline">
                    <!-- <label class="mr-2" for="archive_date">Arşiv &nbsp; &nbsp;</label> -->
                    <div class="input-group mb-3" aria-label="Arşiv Arama">
                        <input  class="form-control" aria-describedby="basic-addon2" type="date" id="archive_date"
                                    max="{{ date('Y-m-d') }}" value="{{ isset($selectedDate) ? $selectedDate :  date('Y-m-d') }}">
                        <!-- <div class="input-group-append">
                            <button class="btn btn-outline-light" type="button">Getir</button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">

            @forelse ($enews as $news)

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card position-relative text-center">
                        <a href="{{route('home.enews-detail', ['id' => $news->id])}}">
                            <img src="{{ imageCheck($news->images) }}" class="card-img-top" alt="{{ $news->title }}" width = "100%" height="auto"
                                onerror="this.onerror=null;this.src='/resimler/enews_blank.png'">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">{{ $news->title }}</h5>
                            </div>
                        </a>

                    </div>
                </div>


            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <strong>Üzgünüz!</strong> Seçtiğiniz tarihe ait e-gazete bulunmamaktadır.
                    </div>
                </div>

            @endforelse




        </div>


    </div>

</script>


@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {
            // Tarih seçimi yapıldığında sayfayı yenile
            $('#archive_date').change(function() {
                var selectedDate = $(this).val();
                var url = "{{ route('home.enews') }}?date=" + selectedDate;
                window.location.href = url;
            });
        });
    </script>
@endsection
