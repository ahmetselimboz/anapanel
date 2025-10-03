@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>{{ $photo_gallery->title }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($photo_gallery->detail, 160) }}">

    <meta property="og:title" content="{{ $photo_gallery->title }}" />
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($photo_gallery->detail, 160) }}" />
    <meta property="og:image" content="{{ imageCheck($photo_gallery->images) }}" />
    <meta property="og:url" content="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" />
    <meta property="og:type" content="photogallery" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if(isset($magicbox["tw_name"])) {{$magicbox["tw_name"]}} @endif" />
    <meta name="twitter:title" content="{{ $photo_gallery->title }}" />
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($photo_gallery->detail, 160) }}" />
    <meta name="twitter:image" content="{{ imageCheck($photo_gallery->images) }}" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Foto Galeriler",
        "item": "{{ route('photo_galleries') }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "{{ $photo_gallery->title }}",
        "item": "@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif"
      }]
    }
    </script>
@endsection

@section('content')
<!-- Photo Gallery Detail (Foto Galeri Detay) -->
<div class="bg-photo-gallery mb-4">
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <nav class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-gray"><a href="{{ route('photo_galleries') }}" title="Foto Galeriler" class="externallink">Foto Galeriler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ html_entity_decode($photo_gallery->title) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="photo-gallery-thumbnail">
                                <img src="
                                {{ route('resizeImage', ["i_url" => imageCheck($photo_gallery->images), "w" => 778, "h" => 438]) }}
                                    " alt="{{ html_entity_decode($photo_gallery->title) }}" class="w-100 rounded-1 lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="photo-gallery-detail-headline mb-4 text-white" id="detailContent">
                    <h2 class="photo-gallery-detail-title">{{ html_entity_decode($photo_gallery->title) }}</h2>
                    <p class="photo-gallery-detail-desc text-white" style="color:#FFFFFF !important;">{!! html_entity_decode($photo_gallery->detail) !!}</p>
                </div>

            </div>
        </div>
    </div>
</div>

@if(adsCheck($ads13->id) && adsCheck($ads13->id)->publish==0)
    <div class="container mb-4">
        @if(adsCheck($ads13->id)->type==1)
            <div class="ad-block">{!! adsCheck($ads13->id)->code !!}</div>
        @else
            <div class="ad-block">
                <a href="{{ adsCheck($ads13->id)->url }}" title="Reklam {{$ads13->id}}" class="externallink">
                    <img src="{{ asset(adsCheck($ads13->id)->images) }}" alt="Reklam {{$ads13->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads13->id)->type }}" height="{{ adsCheck($ads13->id)->height }}" width="{{ adsCheck($ads13->id)->width }}">
                </a>
            </div>
        @endif
    </div>
@endif

<div class="container">
    <div class="row">
        @foreach($photo_gallery_images as $key => $pg_images)
            <div class="col-12 col-md-10 offset-md-1 mb-4">
                <img class="img-fluid rounded-1 mb-4 lazy w-100" src="{{ imageCheck($pg_images->images) }}" alt="{{ html_entity_decode($pg_images->title) }}" />
                <div class="photo-detail-caption">
                    <div class="gallery-caption-num"> {{ $key+1 }} </div>
                    <p class="mb-0">{{ html_entity_decode($pg_images->title) }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
























