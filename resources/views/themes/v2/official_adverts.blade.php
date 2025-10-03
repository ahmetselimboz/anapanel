@extends($theme_path . '.frontend_layout')

@section('meta')
    <title>Resmi İlanlar Haberleri, En Güncel Gelişmeler</title>
    <meta name="description" content="Resmi ilanları haber sitemizden takip edebilirsiniz.">
    <meta name="keywords" content="resmi ilanlar, resmi ilanlar haberleri">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="title" content="Resmi İlanlar Haberleri, En Güncel Gelişmeler">
    <meta name="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="max-image-preview:large">
    <meta property="og:image"
        content="https://gazetebunet.teimg.com/gazetebu-net/uploads/2023/08/thumbs-b-c-69427a71d1935b63cc51435d40143376.jpg">
    <meta property="og:description" content="Resmi ilanları haber sitemizden takip edebilirsiniz.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Resmi İlanlar Haberleri, En Güncel Gelişmeler">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@lidergazete">
    <meta name="twitter:title" content="Resmi İlanlar Haberleri, En Güncel Gelişmeler">
    <meta name="twitter:description" content="Resmi ilanları haber sitemizden takip edebilirsiniz.">
    <meta name="twitter:image"
        content="https://gazetebunet.teimg.com/gazetebu-net/uploads/2023/08/thumbs-b-c-69427a71d1935b63cc51435d40143376.jpg">
@endsection

@section('custom_css')
    <style>
        .header {
            /* background-color: #0000ff; */
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
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


        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            .search-section {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container my-4">

        <div class="row g-3">

            @foreach ($officialAdverts as $official)

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card position-relative">
                        <a href="{{route('home.offficial_advert_detail',$official->id)}}">
                            <span class="badge-custom">RESMİ İLANDIR</span>
                            <img src="{{ imageCheck($official->images) }}" class="card-img-top" alt="{{ $official->title }}" width = "100%" height="250"
                            onerror="this.onerror=null;this.src='/frontend/assets/defaultimagestatic.jpeg'">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">{{ $official->title }}</h5>
                            </div>
                        </a>

                    </div>
                </div>
            @endforeach
            <div class="mt-5">
                            {!! $officialAdverts->links() !!}

            </div>


        </div>

        <form action="{{route('home.offficial_advert')}}" id="official-search" method="get">
            <div class="search-section mt-4 p-3 d-flex align-items-center">
                <input type="date" class="form-control me-2" name="date" value="{{ date('Y-m-d') }}">
                <input type="text" class="form-control me-2" name="ilan_id" placeholder="İlan numarasına göre filtrele">
                <button class="btn btn-primary" type="submit">ARA</button>
            </div>
        </form>
    </div>
    </div>
@endsection
