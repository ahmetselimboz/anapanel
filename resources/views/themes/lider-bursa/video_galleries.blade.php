@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>Vide Galeriler</title>
    <meta name="description" content="Video Galeriler">
@endsection

@section('content')
<!-- Headline (Ana Manşet) -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Video Galeri</h2>
                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
            </div>
        </div>
        <div class="col-12"><!-- (Galeri Manşet) -->

            @foreach($video_galleries as $key => $video_gallery)
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="video-gallery-thumbnail mb-4">
                                        <a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" title="{{ html_entity_decode($video_gallery->title) }}" class="externallink">
                                            <img src="
                                            {{ route('resizeImage', ["i_url" => imageCheck($video_gallery->images), "w" => 778, "h" => 438]) }}
                                                " alt="{{ html_entity_decode($video_gallery->title) }}" class="w-100 rounded-1 lazy">
                                            <i class="video-play-icon"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="video-gallery-headline mb-4">
                            <h2 class="video-gallery-headline-title text-truncate-line-2">{{ html_entity_decode($video_gallery->title) }} </h2>
                            <p class="video-gallery-headline-desc text-truncate-line-3">{!! html_entity_decode($video_gallery->detail) !!} </p>
                        </div>

                        @if(adsCheck($ads14->id) && adsCheck($ads14->id)->publish==0)
                            @if(adsCheck($ads14->id)->type==1)
                                <div class="ad-block">{!! adsCheck($ads14->id)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck($ads14->id)->url }}" title="Reklam {{$ads14->id}}" class="externallink">
                                        <img src="{{ asset(adsCheck($ads14->id)->images) }}" alt="Reklam {{$ads14->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads14->id)->type }}" height="{{ adsCheck($ads14->id)->height }}" width="{{ adsCheck($ads14->id)->width }}">
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @break($key==0)
            @endforeach

            <div class="row">
                @foreach($video_galleries as $key => $video_gallery)
                    @if($key>0 and $key<7)
                        <div class="col-12 col-md-3 mb-4"><!-- (Galeri Kartı) -->
                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                <a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($video_gallery->title) }}">
                                    <img src="
                                    {{ route('resizeImage', ["i_url" => imageCheck($video_gallery->images), "w" => 280, "h" => 150]) }}
                                        " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($video_gallery->title) }}">
                                </a>
                                <div class="card-body bg-black">
                                    <a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($video_gallery->title) }}">
                                        <h5 class="card-title category-card-title text-truncate-line-2 text-white">{{ html_entity_decode($video_gallery->title) }}</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>

@if(adsCheck($ads15->id) && adsCheck($ads15->id)->publish==0)
    <div class="container mb-4">
        @if(adsCheck($ads15->id)->type==1)
            <div class="ad-block">{!! adsCheck($ads15->id)->code !!}</div>
        @else
            <div class="ad-block">
                <a href="{{ adsCheck($ads15->id)->url }}" title="Reklam {{$ads15->id}}" class="externallink">
                    <img src="{{ asset(adsCheck($ads15->id)->images) }}" alt="Reklam {{$ads15->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads15->id)->type }}" height="{{ adsCheck($ads15->id)->height }}" width="{{ adsCheck($ads15->id)->width }}">
                </a>
            </div>
        @endif
    </div>
@endif

<!-- Other Galleries (Diğer Galerilar) -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Diğer Galeriler</h2>
                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
            </div>
        </div>

        @foreach($video_galleries as $key => $video_gallery)
            @if($key>7)
                <div class="col-6 col-lg-3 mb-4"><!-- (Video Kartı) -->
                    <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                        <a href="{{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }}" class="externallink" title="{{ html_entity_decode($video_gallery->title) }}">
                            <img src="
                            {{ route('resizeImage', ["i_url" => imageCheck($video_gallery->images), "w" => 279, "h" => 185]) }}
                                " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($video_gallery->title) }}">
                        </a>
                        <div class="card-body">
                            <a href="{{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }}" class="externallink" title="{{ html_entity_decode($video_gallery->title) }}">
                                <h5 class="card-title news-card-title">{{ html_entity_decode($video_gallery->title) }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
    {{ $video_galleries->links() }}
</div>

@endsection





























