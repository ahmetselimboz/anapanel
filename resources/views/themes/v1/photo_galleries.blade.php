@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>Foto Galeriler</title>
    <meta name="description" content="Foto Galeriler">
@endsection

@section('content')

<!-- Headline (Ana Manşet) -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Foto Galeriler</h2>
                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
            </div>
        </div>
        <div class="col-12"><!-- (Galeri Manşet) -->

            @foreach($photo_galleries as $key => $photo_gallery)
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="photo-gallery-thumbnail mb-4">
                                        <a href="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" title="{{ $photo_gallery->title }}" class="externallink">
                                            <img src="
                                            {{ route('resizeImage', ["i_url" => imageCheck($photo_gallery->images), "w" => 778, "h" => 438]) }}
                                                " alt="{{ html_entity_decode($photo_gallery->title) }}" class="w-100 rounded-1 lazy">
                                            <i class="gallery-image-icon"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="photo-gallery-headline mb-4">
                            <h2 class="photo-gallery-headline-title text-truncate-line-2">{{ html_entity_decode($photo_gallery->title) }} </h2>
                            <p class="photo-gallery-headline-desc text-truncate-line-3">{!! html_entity_decode($photo_gallery->detail) !!} </p>
                        </div>

                        @if(adsCheck($ads11->id) && adsCheck($ads11->id)->publish==0)
                            @if(adsCheck($ads11->id)->type==1)
                                <div class="ad-block">{!! adsCheck($ads11->id)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck($ads11->id)->url }}" title="Reklam {{$ads11->id}}" class="externallink">
                                        <img src="{{ asset('uploads/'.adsCheck($ads11->id)->images) }}" alt="Reklam {{$ads11->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads11->id)->type }}" height="{{ adsCheck($ads11->id)->height }}" width="{{ adsCheck($ads11->id)->width }}">
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @break($key==0)
            @endforeach

            <div class="row">
                @foreach($photo_galleries as $key => $photo_gallery)
                    @if($key>0 and $key<7)
                        <div class="col-6 col-md-4 col-lg-2 mb-4"><!-- (Galeri Kartı) -->
                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                <a href="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($photo_gallery->title) }}">
                                    <img src="
                                    {{ route('resizeImage', ["i_url" => imageCheck($photo_gallery->images), "w" => 179, "h" => 119]) }}
                                        " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($photo_gallery->title) }}">
                                </a>
                                <div class="card-body bg-black">
                                    <a href="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($photo_gallery->title) }}">
                                        <h5 class="card-title category-card-title text-truncate-line-2 text-white">{{ html_entity_decode($photo_gallery->title) }}</h5>
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

@if(adsCheck($ads12->id) && adsCheck($ads12->id)->publish==0)
    <div class="container mb-4">
        @if(adsCheck($ads12->id)->type==1)
            <div class="ad-block">{!! adsCheck($ads12->id)->code !!}</div>
        @else
            <div class="ad-block">
                <a href="{{ adsCheck($ads12->id)->url }}" title="Reklam {{$ads12->id}}" class="externallink">
                    <img src="{{ asset('uploads/'.adsCheck($ads12->id)->images) }}" alt="Reklam {{$ads12->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads12->id)->type }}" height="{{ adsCheck($ads12->id)->height }}" width="{{ adsCheck($ads12->id)->width }}">
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

        @foreach($photo_galleries as $key => $photo_gallery)
            @if($key>7)
                <div class="col-6 col-lg-3 mb-4"><!-- (Video Kartı) -->
                    <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                        <a href="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($photo_gallery->title) }}">
                            <img src="
                            {{ route('resizeImage', ["i_url" => imageCheck($photo_gallery->images), "w" => 279, "h" => 185]) }}
                                " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($photo_gallery->title) }}">
                        </a>
                        <div class="card-body">
                            <a href="@if($photo_gallery->category!=null) {{ route('photo_gallery', ['categoryslug'=>$photo_gallery->category->slug,'slug'=>$photo_gallery->slug,'id'=>$photo_gallery->id]) }} @endif" class="externallink" title="{{ html_entity_decode($photo_gallery->title) }}">
                                <h5 class="card-title news-card-title">{{ html_entity_decode($photo_gallery->title) }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
    {{ $photo_galleries->links() }}
</div>


@endsection



























