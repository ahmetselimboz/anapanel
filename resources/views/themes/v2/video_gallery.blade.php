@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>{{ html_entity_decode($video_gallery->title) }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($video_gallery->detail, 160) }}">
    <meta name="title" content="{{ $video_gallery->title }}">
    <meta name="datePublished" content="{{ $video_gallery->created_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="dateModified" content="{{ $video_gallery->updated_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="articleSection" content="video">
    <meta name="articleAuthor" content="HABER MERKEZİ">
    <meta property="og:title" content="{{ $video_gallery->title }}" />
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($video_gallery->detail, 160) }}" />
    <meta property="og:image" content="{{ imageCheck($video_gallery->images) }}" />
    <meta property="og:url" content="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" />
    <meta property="og:type" content="photogallery" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if(isset($magicbox["tw_name"])) {{$magicbox["tw_name"]}} @endif" />
    <meta name="twitter:title" content="{{ $video_gallery->title }}" />
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($video_gallery->detail, 160) }}" />
    <meta name="twitter:image" content="{{ imageCheck($video_gallery->images) }}" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Video Galeriler",
        "item": "{{ route('video_galleries') }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "{{ $video_gallery->title }}",
        "item": "@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif"
      }]
    }
    </script>
@endsection

@section('content')
<!-- Video Detail (Video Detay) -->
<div class="mb-4">
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <nav class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-gray"><a href="{{ route('video_galleries') }}" class="externallink" title="VİDEO GALERİLER">VİDEO GALERİLER</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">{{ html_entity_decode(html_entity_decode($video_gallery->title)) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="video-player set-iframe mb-4">
                                <div class="video-gallery-thumbnail playout-button ">
                                    <img src="
                                    {{ route('resizeImage', ["i_url" => imageCheck($video_gallery->images), "w" => 679, "h" => 382]) }}
                                        " alt="{{ html_entity_decode(html_entity_decode($video_gallery->title)) }}" class="w-100 rounded-1 lazy">
                                    <i class="video-play-icon"></i>
                                </div>
                                <div class="video-container video-16-9">
                                    {!! $video_gallery->embed !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="video-detail-headline mb-4 text-dark" id="detailContent">
                    <h2 class="video-detail-title text-dark">{{ html_entity_decode(html_entity_decode($video_gallery->title)) }}</h2>
                    <p class="video-detail-desc text-dark" >{!! html_entity_decode($video_gallery->detail) !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Videos (Diğer Videolar) -->
<div class="container">
    <div class="row">
        @foreach($other_videos as $other_video)
            <div class="col-12 col-md-3 mb-4"><!-- (Video Kartı) -->
                <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                    <a href="@if($other_video->category!=null) {{ route('video_gallery', ['categoryslug'=>$other_video->category->slug,'slug'=>$other_video->slug,'id'=>$other_video->id]) }} @endif" title="{{ html_entity_decode($other_video->title) }}" class="externallink">
                        <img src="
                        {{ route('resizeImage', ["i_url" => imageCheck($other_video->images), "w" => 280, "h" => 150]) }}
                            " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($other_video->title) }}">
                    </a>
                    <div class="card-body bg-black">
                        <a href="@if($other_video->category!=null) {{ route('video_gallery', ['categoryslug'=>$other_video->category->slug,'slug'=>$other_video->slug,'id'=>$other_video->id]) }} @endif" title="{{ html_entity_decode(html_entity_decode($video_gallery->title)) }}" class="externallink">
                            <h5 class="card-title category-card-title text-truncate-line-2 text-white">{{ html_entity_decode($other_video->title) }}</h5>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="row"><!--Yorumlar-->
                <div class="col-12 mb-4">
                    <div class="comment-block shadow-sm p-4 overflow-hidden rounded-1">
                        <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                            <h2 class="text-black">BİR CEVAP YAZ</h2>
                            <div class="headline-block-indicator border-0"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
                        </div>
                        <p class="comment-desc">E-posta hesabınız yayımlanmayacak. Gerekli alanlar * ile işaretlenmişlerdir</p>

                        <div class="comment-form">
                            <form action="{{ route('commentsubmit', ['type'=>2,'post_id'=>$video_gallery->id]) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="detail" class="form-control" id="commentMessage" aria-describedby="commentMessage" placeholder="Yorumunuz *"></textarea>
                                    <div id="commentReply"></div>
                                </div>
                                <div class="mb-3">
                                    <input name="name" type="text" class="form-control" id="commentName" aria-describedby="commentName" placeholder="Adınız *">
                                </div>
                                <div class="mb-3">
                                    <input name="email" type="email" class="form-control" id="CommentEmail" aria-describedby="CommentEmail" placeholder="E-posta *">
                                </div>
                                <div class="mb-4 text-end">
                                    <button type="submit" class="btn btn-comment">YORUM GÖNDER</button>
                                </div>
                            </form>
                        </div>

                        <div class="comments-list">
                            <div class="comments-header justify-content-between">
                                <div class="comments-header-title">
                                    Yorumlar <span>({{count($comments)}} Yorum)</span>
                                </div>
                                <div class="comments-sorts d-none">
                                    Yorum Sıralaması:
                                    <select name="" id="comments-sort">
                                        <option value="">En Popüler</option>
                                        <option value="">En Son Eklenen</option>
                                        <option value="">En Beğnilen</option>
                                    </select>
                                </div>
                            </div>


                            @if(count($comments)>0)
                                @foreach($comments as $comment)
                                    <div class="comment-item">
                                        <div class="comment-user-image">
                                            <img src="images/user-profile.jpg" alt="" class="img-fluid lazy">
                                        </div>
                                        <div class="comment-item-title">
                                            {{ $comment->title }} <span class="comment-date">{{ date('d %M Y, H:i', strtotime($comment->created_at)) }}</span>
                                        </div>
                                        <p class="comment-message">{{ $comment->detail }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(adsCheck($ads16->id) && adsCheck($ads16->id)->publish==0)
            <div class="col-12 col-lg-4">
                @if(adsCheck($ads16->id)->type==1)
                    <div class="ad-block">{!! adsCheck($ads16->id)->code !!}</div>
                @else
                    <div class="ad-block">
                        <a href="{{ adsCheck($ads16->id)->url }}" title="Reklam {{$ads16->id}}" class="externallink">
                            <img src="{{ asset(adsCheck($ads16->id)->images) }}" alt="Reklam {{$ads16->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads16->id)->type }}" height="{{ adsCheck($ads16->id)->height }}" width="{{ adsCheck($ads16->id)->width }}">
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection



















