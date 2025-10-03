@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
<title>{{ $article->title }}</title>
<meta name="title" content="{{ $article->title }}">
<meta name="description" content="{{ \Illuminate\Support\Str::limit($article->detail, 160) }}">
<link rel="canonical" href="{{ url()->current() }}" />
<meta name="url" content="{{ url()->current() }}">
<meta name="articleSection" content="columnist">
<meta property="og:title" content="{{ $article->title }}" />
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($article->detail, 160) }}" />
<meta property="og:image" content=" {{ $article->author?->avatar ?? ''}}  " />
<meta property="og:url" content="{{route('frontend_article', ['author'=>slug_format($article->author?->name),'slug' => $article['slug']])   }}" />
<meta property="og:type" content="article" />

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@if(isset($magicbox[" tw_name"])) {{$magicbox["tw_name"]}} @endif" />
<meta name="twitter:title" content="{{ $article->title }}" />
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($article->detail, 160) }}" />
<meta name="twitter:image" content="{{ $article->author?->avatar ?? ''}}" />

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "@id": "#Article",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "#webpage",
            "url": "{{ route('frontend_article', ['author'=>slug_format($article->author?->name),'slug' => $article['slug']]) }}"
        },
        "name": "{{ $article->title }}",
        "description": "{{ \Illuminate\Support\Str::limit($article->detail, 160) }}",
        "image": "@if($article->author!=null) {{ $article->author->avatar }} @endif",
        "datePublished": "{{ date('Y-m-d', strtotime($article->created_at)) }}",
        "dateModified": "{{ date('Y-m-d', strtotime($article->uploaded_at)) }}",
        "author": "@if($article->author!=null) {{ $article->author?->name }} @endif",
        "publisher": {
            "@type": "Organization",
            "name": "{{ $settings['title'] }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('uploads/'.$settings['logo']) }}"
            }
        }
    }
</script>

@endsection

@section("custom_css")
<style>
    .author-card {
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .author-info {
        display: flex;
        align-items: center;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
    }

    .author-name {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 14px;
    }

    .author-title {
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 0;
    }

    .publish-info {

        font-size: 14px;
        font-weight: 700;
    }

    .reading-info {
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
@if(adsCheck($ads18->id) && adsCheck($ads18->id)->publish==0)
<div class="container mb-4">
    @if(adsCheck($ads18->id)->type==1)
    <div class="ad-block">{!! adsCheck($ads18->id)->code !!}</div>
    @else
    <div class="ad-block">
        <a href="{{ adsCheck($ads18->id)->url }}" title="Reklam {{$ads18->id}}" class="externallink">
            <img src="{{ asset(adsCheck($ads18->id)->images) }}" alt="Reklam {{$ads18->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads18->id)->type }}" height="{{ adsCheck($ads18->id)->height }}" width="{{ adsCheck($ads18->id)->width }}">
        </a>
    </div>
    @endif
</div>
@endif


    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/yazarlar">YAZARLAR</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if ($article->author != null)
                                {{ $article->author->name }}
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="col-12 col-lg-8">
                <div class="row"><!-- Yazar Kartı -->
                    <div class="col-12 mb-4">
                        <div class="author-detail-card overflow-hidden rounded-1 border-0">
                            <div class="author-detail-article">
                                <h5 class="text-truncate-line-2">{{ html_entity_decode($article->title) }}</h5>
                            </div>
                            <div class="author-detail-footer d-flex justify-content-between align-items-center">

                                <a href="#" title="yazar link">
                                    <img src="
                                        {{ route('resizeImage', ['i_url' => $article->author?->avatar ?? 'anonim', 'w' => 100, 'h' => 100]) }}"
                                        class="img-thumbnail lazy" alt="{{ $article->author?->name ?? '' }}">
                                    <div class="w-100 ">
                                        <div>
                                            <h6 class="text-white mb-0 text-truncate">
                                                @if ($article->author != null)
                                                    {{ $article->author->name }}
                                                @endif
                                            </h6>

                                        </div>

                                    </div>
                                </a>
                                <div class="d-none d-md-flex flex-row align-items-center flex-nowrap">

                                    <div class="d-flex flex-column align-items-center justify-content-center border-start border-end border-white px-3"
                                        style="--bs-border-opacity: .3;">
                                        <p class="publish-info text-white my-1 ">
                                            {{ date('d.m.Y - H:i', strtotime($article->created_at)) }}</p>
                                        <span class="reading-info text-white mb-1">YAYINLANMA</span>
                                    </div>
                                    @if ($article->created_at != $article->updated_at)
                                        <div class="d-flex flex-column align-items-center justify-content-center border-end border-white px-3"
                                            style="--bs-border-opacity: .3;">
                                            <p class="publish-info text-white my-1">
                                                {{ date('d.m.Y - H:i', strtotime($article->updated_at)) }}</p>
                                            <span class="reading-info text-white mb-1">GÜNCELLEME</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="author-detail-footer w-100 d-flex justify-content-end d-md-none px-2 mt-2 rounded-1">
                            <div class=" d-flex flex-row align-items-center flex-nowrap">

                                <div class="d-flex flex-column align-items-center justify-content-center border-start border-end border-white px-3"
                                    style="--bs-border-opacity: .3;">
                                    <p class="publish-info text-white my-1 ">
                                        {{ date('d.m.Y - H:i', strtotime($article->created_at)) }}</p>
                                    <span class="reading-info text-white mb-1">YAYINLANMA</span>
                                </div>
                                @if ($article->created_at != $article->updated_at)
                                    <div class="d-flex flex-column align-items-center justify-content-center border-end border-white px-3"
                                        style="--bs-border-opacity: .3;">
                                        <p class="publish-info text-white my-1">
                                            {{ date('d.m.Y - H:i', strtotime($article->updated_at)) }}</p>
                                        <span class="reading-info text-white mb-1">GÜNCELLEME</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                @if (!blank($article->images))
                    <img src="{{ imageCheck($article->images) }}" class="detayresim"
                        style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);" alt="{{ html_entity_decode($article->title) }}">
                @endif



                <div class="detail-content mb-4" id="detailContent">
                    <p>{!! $article->detail !!}</p>
                    <!-- Banner -->
                    @if (adsCheck($ads19->id) && adsCheck($ads19->id)->publish == 0)
                        <div class="mb-4">
                            @if (adsCheck($ads19->id)->type == 1)
                                <div class="ad-block">{!! adsCheck($ads19->id)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck($ads19->id)->url }}" title="Reklam {{ $ads19->id }}"
                                        class="externallink">
                                        <img src="{{ asset(adsCheck($ads19->id)->images) }}"
                                            alt="Reklam {{ $ads19->id }}" class="img-fluid lazy"
                                            data-type="{{ adsCheck($ads19->id)->type }}"
                                            height="{{ adsCheck($ads19->id)->height }}"
                                            width="{{ adsCheck($ads19->id)->width }}">
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="w-100">
                    <div class="author-detail-footer w-100 d-flex px-2 mt-2 rounded-1">
                        <div class="author-social w-100 mt-1 pt-1">
                            <div class="d-flex justify-content-center flex-wrap flex-lg-nowrap">
                                <div class="social-button mb-4"><a href="whatsapp://send?text={{ $article->title }}"
                                        data-action="share/whatsapp/share" class="social-link shadow-sm externallink"><i
                                            class="whatsapp"></i></a></div>
                                <div class="social-button mb-4"><a
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                                        class="social-link shadow-sm externallink"><i class="facebook"></i></a></div>
                                <div class="social-button mb-4"><a
                                        href="https://twitter.com/intent/tweet?text={{ $article->title }}&url={{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                                        class="social-link shadow-sm externallink"><i class="x-corp"></i></a></div>
                                <div class="social-button mb-4"><a class="social-link shadow-sm" id="copyDetail"><i
                                            class="copy-paste"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row"><!--Yorumlar-->
                    <div class="col-12 mb-4">
                        <div class="comment-block shadow-sm p-4 overflow-hidden rounded-1">
                            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                <h2 class="text-black">BİR CEVAP YAZ</h2>
                                <div class="headline-block-indicator border-0">
                                    <div class="indicator-ball" style="background-color:#EC0000;"></div>
                                </div>
                            </div>
                            <p class="comment-desc">E-posta hesabınız yayımlanmayacak. Gerekli alanlar * ile
                                işaretlenmişlerdir</p>

                            <div class="comment-form">
                                <form action="{{ route('commentsubmit', ['type' => 3, 'post_id' => $article->id]) }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="detail" class="form-control" id="commentMessage" aria-describedby="commentMessage"
                                            placeholder="Yorumunuz *"></textarea>
                                        <div id="commentReply"></div>
                                    </div>
                                    <div class="mb-3">
                                        <input name="name" type="text" class="form-control" id="commentName"
                                            aria-describedby="commentName" placeholder="Adınız *">
                                    </div>
                                    <div class="mb-3">
                                        <input name="email" type="email" class="form-control" id="CommentEmail"
                                            aria-describedby="CommentEmail" placeholder="E-posta *">
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


                                @if (count($comments) > 0)
                                    @foreach ($comments as $comment)
                                        <div class="comment-item">
                                            <div class="comment-user-image">
                                                <img src="{{ asset('frontend/assets/images/user-profile.jpg') }}"
                                                    alt="{{ $article . ' ' . $comment->title }} yorumu"
                                                    class="img-fluid lazy">
                                            </div>
                                            <div class="comment-item-title">
                                                {{ $comment->title }} <span
                                                    class="comment-date">{{ date('d %M Y, H:i', strtotime($comment->created_at)) }}</span>
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



            <!--Sağ block-->
            <div class="col-12 col-lg-4">
                <div class="row">

                    <!--Diğer Yazıları-->
                    <div class="col-12 mb-4">
                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm">
                            <h2 class="mostly-block-headline mb-4 text-uppercase">Diğer Yazıları</h2>

                            <div class="container-fluid mb-3 pb-1">
                                <div class="row">
                                    @foreach ($other_articles as $article)
                                        <div class="col-12">
                                            <div class="card border-0 position-relative">
                                                <div class="card-body py-0 ps-2">
                                                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                                                        class="externallink"
                                                        title="{{ html_entity_decode($article->title) }}">
                                                        <h5 class="author-other-article text-truncate-line-2">
                                                            {{ html_entity_decode($article->title) }}</h5>
                                                    </a>
                                                    <p class="card-text mostly-desc mb-0">
                                                        {{ date('d.m.Y - H:i', strtotime($article->created_at)) }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    @endforeach



                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                @if (adsCheck($ads20->id) && adsCheck($ads20->id)->publish == 0)
                    @if (adsCheck($ads20->id)->type == 1)
                        <div class="ad-block">{!! adsCheck($ads20->id)->code !!}</div>
                    @else
                        <div class="ad-block">
                            <a href="{{ adsCheck($ads20->id)->url }}" title="Reklam {{ $ads20->id }}"
                                class="externallink">
                                <img src="{{ asset(adsCheck($ads20->id)->images) }}" alt="Reklam {{ $ads20->id }}"
                                    class="img-fluid lazy" data-type="{{ adsCheck($ads20->id)->type }}"
                                    height="{{ adsCheck($ads20->id)->height }}"
                                    width="{{ adsCheck($ads20->id)->width }}">
                            </a>
                        </div>
                    @endif
                @endif


            </div>
        </div>
    </div>

    <style>
        .detail-content * {
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content,
        .detail-content p,
        .detail-content span {
            font-size: 18px !important;
            font-weight: 500 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content strong {
            font-size: 18px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h1 {
            font-size: 25px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h2 {
            font-size: 23px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h3 {
            font-size: 22px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h4 {
            font-size: 21px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h5 {
            font-size: 20px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .detail-content h6 {
            font-size: 19px !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif !important;
        }
    </style>

@endsection
