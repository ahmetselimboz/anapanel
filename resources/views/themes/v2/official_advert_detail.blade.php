@extends('themes.' . $theme . '.frontend_layout')
@php
    $clsfad = $officialAdvert->clsfadmagicbox;
    $images = isset($clsfad['images']) ? $clsfad['images'] : null;
    $tags = isset($clsfad['news_tags']) ? $clsfad['news_tags'] : null;
    $link = isset($clsfad['link']) && filter_var($clsfad['link'], FILTER_VALIDATE_URL) ? $clsfad['link'] : null;
    $description = preg_replace('/\s+/', ' ', trim(html_entity_decode(strip_tags($officialAdvert->detail))));
    dd($description);
@endphp

@section('meta')
    <title>{{ $officialAdvert->title }}</title>
    <meta name="title" content="{{ $officialAdvert->title }}">
    <meta name="description" content="{!! \Illuminate\Support\Str::limit($description, 160) !!}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="max-image-preview:large">
    <meta property="og:title" content="{{ $officialAdvert->title }}" />
    <meta property="og:description"
        content="{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($officialAdvert->detail))) !!}" />
    <meta property="og:image" content="{{ imageCheck($officialAdvert->images) }}" />
    <meta property="og:url" content="{{ route('home.offficial_advert_detail', ['id' => $officialAdvert->id]) }}" />
    <meta property="og:type" content="news" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if (isset($magicbox['tw_name'])) {{ $magicbox['tw_name'] }} @endif" />
    <meta name="twitter:title" content="{{ $officialAdvert->title }}" />
    <meta name="twitter:description"
        content="{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($officialAdvert->detail))) !!}" />
    <meta name="twitter:image" content="{{ imageCheck($officialAdvert->images) }}" />

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "NewsArticle",
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ route('home.offficial_advert_detail', ['id' => $officialAdvert->id]) }}"
          },
          "headline": "{{ $officialAdvert->title }}",
          "description": "{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($officialAdvert->detail))) !!}",
          "image": "{{ imageCheck($officialAdvert->images) }}",
          "author": {
            "@type": "Organization",
            "name": "Yazar",
          },
          "publisher": {
            "@type": "Organization",
            "name": "{{ $settings['title'] }}",
            "logo": {
              "@type": "ImageObject",
              "url": "{{ imageCheck($settings['logo']) }}"
            }
          },
          "datePublished": "{{ date('Y-m-d', strtotime($officialAdvert->created_at)) }}",
          "dateModified": "{{ date('Y-m-d', strtotime($officialAdvert->updated_at)) }}"
        }
        </script>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "Resmi İlanlar",
            "item": ""
          },{
            "@type": "ListItem",
            "position": 2,
            "name": "{{ $officialAdvert->title }}",
            "item": "{{ route('home.offficial_advert_detail', ['id' => $officialAdvert->id]) }}"
          }]
        }
        </script>
    <style>
        .official-detail img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
            /* Resmi ortalar */
        }

        .official-detail table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
        }

        .official-detail table th,
        .official-detail table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .official-detail iframe {
            max-width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            /* Video ve gömülü içerikleri oranlı yapar */
        }
    </style>
@endsection

@section('content')
    <div class="container burak 123">
        <div class="row">
            <div class="col-12">
                <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">{{ $officialAdvert->title }}</h2>
                    <div class="headline-block-indicator">
                        <div class="indicator-ball" style="background-color:#975E64;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="col-12 bg-gray rounded-1 mb-4">
                    <div class="container ">
                        <div class="row py-1">
                            <div class="col-12  py-2 pe-md-2 official-detail">
                                {!! $officialAdvert->detail !!}
                            </div>


                            <div class="col-12  py-2 pe-md-2 official-detail">
                                <div class="row">
                                    <span class="bold">
                                        <b>{{"Basın ilan: #" . $officialAdvert->ilan_id}}</b>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>



        </div>
    </div>
    </div>
@endsection