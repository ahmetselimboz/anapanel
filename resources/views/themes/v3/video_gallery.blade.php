@extends('themes.'.$theme.'.frontend_layout')

@section('meta')

    <title>{{ html_entity_decode($video_gallery->title) }}</title>
    <meta name="title" content="{{ $video_gallery->title }}" />
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($video_gallery->detail, 160) }}">

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

    <meta name="title" content="{{ $video_gallery->title }}">
    <meta name="datePublished" content="{{ $video_gallery->created_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="dateModified" content="{{ $video_gallery->updated_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="articleSection" content="video">
    <meta name="articleAuthor" content="HABER MERKEZİ">

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
    <div class="container">

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
                            <div class="video-player mb-4">
                                <div class="video-16-9">
                                    {!! $video_gallery->embed !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">

                <div class="video-detail-headline mb-4" id="detailContent">
                    <h2 class="video-detail-title text-black">{{ html_entity_decode(html_entity_decode($video_gallery->title)) }}</h2>
                    <p class="video-detail-desc text-black">{!! html_entity_decode($video_gallery->detail) !!}</p>
                </div>

                <div class="video-detail-footer">
                    <div class="d-flex justify-content-start">
                        <div class="detail-author-block mb-4 d-none">
                            <div class="detail-author-image">
                                <img src="images/detail-user.svg" alt="" width="100%" class="lazy">
                            </div>
                            <div class="text-truncate">.</div>
                        </div>
                        <div class="detail-added-date mb-2 text-truncate">{{ date('d.m.Y - H:i', strtotime($video_gallery->created_at)) }}</div>
                    </div>

                    <div class="video-detail-social-block pt-3 d-flex justify-content-center justify-content-lg-start flex-lg-nowrap">
                        <div class="social-button mb-3">
                            <a href="whatsapp://send?text={{html_entity_decode(html_entity_decode($video_gallery->title))}}" data-action="share/whatsapp/share" class="social-link shadow-sm btn-whatsapp externallink">
                                <i class="whatsapp"></i>
                            </a>
                        </div>
                        <div class="social-button mb-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=@if($video_gallery->category!=null) {{ route('video_gallery',['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" class="social-link shadow-sm btn-facebook externallink">
                                <i class="facebook"></i>
                            </a>
                        </div>
                        <div class="social-button mb-3">
                            <a href="https://twitter.com/intent/tweet?text={{html_entity_decode(html_entity_decode($video_gallery->title))}}&url=@if($video_gallery->category!=null) {{ route('video_gallery',['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" class="social-link shadow-sm btn-x-corp externallink">
                                <i class="x-corp"></i>
                            </a>
                        </div>
                        <div class="social-button mb-3">
                            <a class="social-link shadow-sm btn-copy" id="copyDetail">
                                <i class="copy-paste"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
	</div>

	<div class="container">
	<div class="spotlar">

		@php $counter = 1; @endphp
		@foreach($other_videos as $other_video)

					<div class="spot spotduz spotduz-{{ $counter }}">
						<a href="@if($other_video->category!=null) {{ route('video_gallery', ['categoryslug'=>$other_video->category->slug,'slug'=>$other_video->slug,'id'=>$other_video->id]) }} @endif" title="{{ $other_video['title'] }}">
						<b>{{ categoryCheck($other_video['category_id'])->title }}</b>
						<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($other_video["images"]), "w" => 377, "h" => 210]) }}" alt="{{html_entity_decode($other_video["title"]) }}" alt="{{ html_entity_decode($other_video["title"]) }}" /></div>
						<p><span>{{html_entity_decode($other_video["title"]) }}</span></p>
						</a>
					</div>

		@php $counter++; @endphp
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

        @if(adsCheck($ads16->id))
            <div class="col-12 col-lg-4">
                @if(adsCheck($ads16->id)->type==1)
                    <div class="ad-block">{!! adsCheck($ads16->id)->code !!}</div>
                @else
                    <div class="ad-block">
                        <a href="{{ adsCheck($ads16->id)->url }}" title="Reklam {{$ads16->id}}" class="externallink">
                            <img src="{{ asset('uploads/'.adsCheck($ads16->id)->images) }}" alt="Reklam {{$ads16->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads16->id)->type }}" height="{{ adsCheck($ads16->id)->height }}" width="{{ adsCheck($ads16->id)->width }}">
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection



















