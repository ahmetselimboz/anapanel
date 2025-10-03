@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>{{ $post->title }}</title>
    <meta name="description" content="{{ $post->description }}">
    <meta name="keywords" content="{{ $post->keywords }}">

    <link rel="canonical" href="{{ route('amppost', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" />

    <meta property="og:title" content="{{ $post->title }}" />
    <meta property="og:description" content="{{ $post->description }}" />
    <meta property="og:image" content="{{ imageCheck($post->images) }}" />
    <meta property="og:url" content="@if($post->category!=null) {{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif" />
    <meta property="og:type" content="news" />
    <meta property="og:image:width" content="777">
    <meta property="og:image:height" content="510">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if(isset($magicbox["tw_name"])) {{$magicbox["tw_name"]}} @endif" />
    <meta name="twitter:title" content="{{ $post->title }}" />
    <meta name="twitter:description" content="{{ $post->description }}" />
    <meta name="twitter:image" content="{{ imageCheck($post->images) }}" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "@if($post->category!=null) {{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif"
      },
      "headline": "{{ $post->meta_title }}",
      "description": "{{ $post->meta_description }}",
      "image": "{{ imageCheck($post->images) }}",
      "author": {
        "@type": "Organization",
        "name": "@if(isset($extra->author)) {{ $extra->author }} @else Yazar @endif"
      },
      "publisher": {
        "@type": "Organization",
        "name": "{{ $settings['title'] }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ imageCheck($settings['logo']) }}"
        }
      },
      "datePublished": "{{ date('Y-m-d', strtotime($post->created_at)) }}",
      "dateModified": "{{ date('Y-m-d', strtotime($post->created_at)) }}"
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "{{ $post->category->title }}",
        "item": "{{ route('category', ['slug'=>$post->category->slug,'id'=>$post->category->id]) }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "{{ html_entity_decode($post->title) }}",
        "item": "@if($post->category!=null) {{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif"
      }]
    }
    </script>

    <style>
    .tag-button {
        color: #000 !important;
        background-color: RGBA(var(--bs-light-rgb), var(--bs-bg-opacity, 1)) !important;
    }
    </style>



<style>

    .detail-content, .detail-content p, .detail-content span {
        font-size: 18px !important;
        font-weight: 300 !important;
        font-family: 'Inter' !important;
    }

    .detail-content strong {
        font-size: 18px !important;
        font-weight: 600 !important;
    }

    .detail-content h1 {
        font-size: 25px !important;
        font-weight: 600 !important;
    }

    .detail-content h2 {
        font-size: 23px !important;
        font-weight: 600 !important;
    }

    .detail-content h3 {
        font-size: 21px !important;
        font-weight: 600 !important;
    }

    .detail-content h4 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .detail-content h5 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .detail-content h6 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    </style>
@endsection

@section('content')

    @if(isset($extra->show_post_ads) and $extra->show_post_ads==0)
        @if(adsCheck($ads8->id) && adsCheck($ads8->id)->publish==0)
            <div class="container mb-4">
                @if(adsCheck($ads8->id)->type==1)
                    <div class="ad-block">{!! adsCheck($ads8->id)->code !!}</div>
                @else
                    <div class="ad-block">
                        <a href="{{ adsCheck($ads8->id)->url }}" title="Reklam {{$ads8->id}}" class="externallink">
                            <img src="{{ asset('uploads/'.adsCheck($ads8->id)->images) }}" alt="Reklam {{$ads8->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads8->id)->type }}" height="{{ adsCheck($ads8->id)->height }}" width="{{ adsCheck($ads8->id)->width }}">
                        </a>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <div class="container" id="infiniteBox">
        <div class="row infiniteContent">
            <div class="col-12">
                <nav class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('category', ['slug'=>$post->category->slug,'id'=>$post->category->id]) }}" class="externallink" title="{{ $post->category->title }}">{{ $post->category->title }}</a></li>
                        <li class="breadcrumb-item active uppercase externallink" aria-current="page">{{ html_entity_decode($post->title) }}</li>
                    </ol>
                </nav>
            </div>

            <div class="col-12">
                <h1 class="detail-title" style="font-size: 44px!important;">{{ html_entity_decode($post->title) }}</h1>
                <p class="detail-spot" style="font-size: 21px!important;">{{ html_entity_decode($post->description) }}</p>
                <hr>
                <div class="detail-footer d-lg-flex justify-content-between">
                    <div class="d-flex flex-wrap w-100">

                        <div class="detail-added-date mb-2">{{ 'Giriş: '.date('d.m.Y - H:i', strtotime($post->created_at)) }}</div>
                        <div class="detail-updated-date mb-4 d-none">{{ 'Güncelleme: '.date('d.m.Y - H:i', strtotime($post->created_at)) }}</div>
                    </div>

                    <div class="d-flex w-100 justify-content-center justify-content-lg-end detail-social-buttons">
                        @if($magicbox["googlenews"]!=null)
                            <div class="social-button mb-4"><a href="{{ $magicbox["googlenews"] }}" title="{{ $magicbox["googlenews"] }}" class="externallink social-link shadow-sm btn-google-news"><i class="google-news"></i></a></div>
                        @endif
                        <div class="social-button mb-4"><a href="whatsapp://send?text={{html_entity_decode($post->title)}}" data-action="share/whatsapp/share" title="{{ $magicbox["googlenews"] }}" class="externallink social-link shadow-sm btn-whatsapp"><i class="whatsapp"></i></a></div>
                        <div class="social-button mb-4"><a href="https://www.facebook.com/sharer/sharer.php?u=@if($post->category!=null) {{ route('post',['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif" title="{{ html_entity_decode($post->title) }}" class="externallink social-link shadow-sm btn-facebook"><i class="facebook"></i></a></div>
                        <div class="social-button mb-4"><a href="https://twitter.com/intent/tweet?text={{html_entity_decode($post->title)}}&url=@if($post->category!=null) {{ route('post',['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif" title="{{ html_entity_decode($post->title) }}" class="externallink social-link shadow-sm btn-x-corp"><i class="x-corp"></i></a></div>
                        <div class="social-button mb-4"><a href="" title="" class="externallink social-link shadow-sm btn-copy" id="copyDetail"><i class="copy-paste"></i></a></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <img src="{{ imageCheck($post->images) }}" class="w-100 rounded-1 mb-4 lazy"
                 alt="{{ html_entity_decode($post->title) }}" style="max-height:510px">
                <div class="detail-content mb-4" id="detailContent">

                    @if(isset($extra->show_post_ads) and $extra->show_post_ads==0)
                        @if(adsCheck($ads9->id) && adsCheck($ads9->id)->publish==0)
                            @if(adsCheck($ads9->id)->type==1)
                                <div class="ad-block">{!! adsCheck($ads9->id)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck($ads9->id)->url }}" title="Reklam {{$ads9->id}}" class="externallink">
                                        <img src="{{ asset('uploads/'.adsCheck($ads9->id)->images) }}" alt="Reklam {{$ads9->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads9->id)->type }}" height="{{ adsCheck($ads9->id)->height }}" width="{{ adsCheck($ads9->id)->width }}">
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif

                    @if(isset($extra->video_embed) and $extra->video_embed!=null)
                        <div class="embed-responsive embed-responsive-16by9">{!! str_replace('class="','class="embed-responsive-item ',$extra->video_embed) !!}</div>
                    @endif


                    <div style="font-size: 1.25rem;">{!! $post->detail !!}</div>


                    @if(isset($extra->related_news) and $extra->related_news>0)
                        <blockquote cite="#" class="d-flex flex-wrap rounded my-4">
                            <div class="blockquote-image">
                                <a href="{{ route('post', ['categoryslug'=>categoryCheck(relatedPostCheck($extra->related_news)->category_id)->slug,'slug'=>relatedPostCheck($extra->related_news)->slug,'id'=>relatedPostCheck($extra->related_news)->id]) }}" title="{{ relatedPostCheck($extra->related_news)->title }}" class="externallink">
                                    <img src="{{ imageCheck(relatedPostCheck($extra->related_news)->images) }}" alt="{{ relatedPostCheck($extra->related_news)->title }}" class="img-fluid lazy">
                                </a>
                            </div>
                            <div class="blockquote-content ms-0 ms-md-4 ms-lg-0 ms-xl-4">
                                <div class="blockquote-title">İLİŞKİLİ YAZI</div>
                                <a href="{{ route('post', ['categoryslug'=>categoryCheck(relatedPostCheck($extra->related_news)->category_id)->slug,'slug'=>relatedPostCheck($extra->related_news)->slug,'id'=>relatedPostCheck($extra->related_news)->id]) }}" title="{{ relatedPostCheck($extra->related_news)->title }}" class="externallink">
                                    <h5>{{ relatedPostCheck($extra->related_news)->title }}</h5>
                                </a>
                            </div>
                        </blockquote>
                    @endif

                </div>
                 @if(!blank($post->keywords))
                    <div class="detail-tags">
                        @foreach(explode(',', $post->keywords) as $k_item)
                            <a href="{{ route('search.get', ['search' => trim($k_item)]) }}"
                                 class="tag-button externallink btn btn-outline-danger keyword-search">
                                 #{{ trim($k_item) }}
                            </a>
                        @endforeach
                    </div>
                @endif
                <div class="w-100">
                    <div class="d-flex w-100 justify-content-center justify-content-lg-end detail-social-buttons">
                        @if($magicbox["googlenews"]!=null)
                            <div class="social-button mb-4"><a href="{{ $magicbox["googlenews"] }}" title="{{ $magicbox["googlenews"] }}" class="externallink social-link shadow-sm btn-google-news"><i class="google-news"></i></a></div>
                        @endif
                        <div class="social-button mb-4"><a href="whatsapp://send?text={{html_entity_decode($post->title)}}" data-action="share/whatsapp/share" title="{{ $magicbox["googlenews"] }}" class="externallink social-link shadow-sm btn-whatsapp"><i class="whatsapp"></i></a></div>
                        <div class="social-button mb-4"><a href="https://www.facebook.com/sharer/sharer.php?u=@if($post->category!=null) {{ route('post',['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" title="{{ html_entity_decode($post->title) }} @endif" class="externallink social-link shadow-sm btn-facebook"><i class="facebook"></i></a></div>
                        <div class="social-button mb-4"><a href="https://twitter.com/intent/tweet?text={{html_entity_decode($post->title)}}&url=@if($post->category!=null) {{ route('post',['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }} @endif" title="{{ html_entity_decode($post->title) }}" class="externallink social-link shadow-sm btn-x-corp"><i class="x-corp"></i></a></div>
                        <div class="social-button mb-4"><a href="" title="" class="externallink social-link shadow-sm btn-copy" id="copyDetail"><i class="copy-paste"></i></a></div>
                    </div>
                </div>



                @if(count($same_post)>0)
                    <div class="row"><!--Bezner Haberler-->
                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                <h2 class="text-black">Benzer Haberler</h2>
                                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
                            </div>
                        </div>
                        @foreach($same_post as $spost)
                            <div class="col-6 col-lg-3 mb-4"><!-- (Haber Kartı) -->
                                <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                    <a href="{{ route('post', ['categoryslug'=>$spost->category->slug,'slug'=>$spost->slug,'id'=>$spost->id]) }}" class="externallink">
                                        <img src="{{ imageCheck($spost->images) }}" class="card-img-top rounded-0 lazy" alt="{{ $spost->title }}">
                                    </a>
                                    <div class="card-body category-card-body">
                                        <a href="{{ route('post', ['categoryslug'=>$spost->category->slug,'slug'=>$spost->slug,'id'=>$spost->id]) }}" class="externallink">
                                            <h5 class="card-title category-card-title text-truncate-line-2">{{ $spost->title }}</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($extra->comment_status==0)
                    <div class="row"><!--Yorumlar-->
                        <div class="col-12 mb-4">
                            <div class="comment-block shadow-sm p-4 overflow-hidden rounded-1">
                                <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                    <h2 class="text-black">BİR CEVAP YAZ</h2>
                                    <div class="headline-block-indicator border-0"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
                                </div>
                                <p class="comment-desc">E-posta hesabınız yayımlanmayacak. Gerekli alanlar * ile işaretlenmişlerdir</p>

                                <div class="comment-form">
                                    <form action="{{ route('commentsubmit', ['type'=>0,'post_id'=>$post->id]) }}" method="post">
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
                                            Yorumlar <span>({{ count($comments) }} Yorum)</span>
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
                                                    {{ $comment->title }} <span class="comment-date">{{ date('d.m.Y, H:i', strtotime($comment->created_at)) }}</span>
                                                </div>
                                                <p class="comment-message">{{ $comment->detail }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>



            <!--Sağ block-->
            <div class="col-12 col-lg-4">
                <div class="row">
                    <!--Öne Çıkanlar-->
                    <div class="col-12 mb-4">
                        <div id="featuredCarousel" class="carousel slide bg-black" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($post_mini_slides->take(15) as $pkey => $post_slide)
                                    <div class="carousel-item @if($pkey==0) active @endif">
                                        <a href="{{ route('post', ['categoryslug'=>$post_slide->category->slug,'slug'=>$post_slide->slug,'id'=>$post_slide->id]) }}" title="{{ $post_slide->title }}" class="externallink">
                                            <div class="featured-item">
                                                <div class="featured-image">
                                                    <img src="{{ imageCheck($post_slide->images) }}" alt="{{ $post_slide->title }}" class="lazy" height="245" style="height:245px !important;">
                                                </div>

                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="carousel-indicators">
                                @foreach($post_mini_slides->take(15) as $pkey => $post_slide)
                                    <button type="button" data-bs-target="#featuredCarousel" data-bs-slide-to="{{$pkey+1}}" @if($pkey==0) class="active" aria-current="true" @endif>{{$pkey+1}}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if(isset($extra->show_post_ads) and $extra->show_post_ads==0)
                        @if(adsCheck($ads10->id) && adsCheck($ads10->id)->publish==0)
                            <div class="col-12 mb-4">
                                @if(adsCheck($ads10->id)->type==1)
                                    <div class="ad-block">{!! adsCheck($ads10->id)->code !!}</div>
                                @else
                                    <div class="ad-block">
                                        <a href="{{ adsCheck($ads10->id)->url }}" title="Reklam {{$ads10->id}}" class="externallink">
                                            <img src="{{ asset('uploads/'.adsCheck($ads10->id)->images) }}" alt="Reklam {{$ads10->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads10->id)->type }}" height="{{ adsCheck($ads10->id)->height }}" width="{{ adsCheck($ads10->id)->width }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                    @endif
                @endif
                <!--En Çok Okunanlar-->
                    <div class="col-12 mb-4">
                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm">
                            <h2 class="mostly-block-headline mb-4 text-uppercase">En Çok Okunanlar</h2>
                            <div class="container-fluid">
                                <div class="row">
                                    @foreach($hit_news as $hit_n)
                                        <div class="col-12">
                                            <div class="card mostly-card position-relative">
                                                <div class="mostly-thumb">
                                                    <a href="{{ route('post', ['categoryslug'=>$hit_n->category->slug,'slug'=>$hit_n->slug,'id'=>$hit_n->id]) }}" title="{{ $hit_n->title }}" class="externallink">
                                                        <img src="{{ imageCheck($hit_n->images) }}" alt="{{ $hit_n->title }}" class="w-100 rounded-1 lazy">
                                                    </a>
                                                </div>
                                                <div class="card-body py-2">
                                                    <a href="{{ route('post', ['categoryslug'=>$hit_n->category->slug,'slug'=>$hit_n->slug,'id'=>$hit_n->id]) }}" title="{{ $hit_n->title }}" class="externallink">
                                                        <h5 class="mostly-title text-truncate-line-2">{{ html_entity_decode($hit_n->title) }}</h5>
                                                    </a>
                                                    <p class="card-text mostly-desc mb-0">{{ 'Giriş: '.date('d/m/Y', strtotime($hit_n->created_at)) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($infiniteurl!=null)
            <a href="{{ route('post', ['categoryslug'=>$infiniteurl->category->slug,'slug'=>$infiniteurl->slug,'id'=>$infiniteurl->id]) }}" id="gopostinfinite">next</a>
        @endif
    </div>
@endsection


@section('custom_js')
    @if($infiniteurl!=null)
        <script type="text/javascript" src="{{ asset('frontend/assets/js/infinite.js') }}"></script>
        <script>
            $('#infiniteBox').cleverInfiniteScroll({
                contentsWrapperSelector: '#infiniteBox',
                contentSelector: '.infiniteContent',
                nextSelector: '#gopostinfinite'
            });
        </script>
    @endif
@endsection


