@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
    <title>{{ $post->title }}</title>
    <meta name="title" content="{{ $post->title }}">
    <meta name="description" content="{{ $post->description }}">
    <meta name="keywords" content="{{ $post->keywords }}">

    <link rel="canonical"
        href="{{ route('amppost', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" />

    <meta property="og:title" content="{{ $post->title }}" />
    <meta property="og:description" content="{{ $post->description }}" />
    <meta property="og:image" content="{{ imageCheck($post->images) }}" />
    <meta property="og:url"
        content="@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif" />
    <meta property="og:type" content="news" />
    <meta property="og:image:width" content="777">
    <meta property="og:image:height" content="510">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if (isset($magicbox['tw_name'])) {{ $magicbox['tw_name'] }} @endif" />
    <meta name="twitter:title" content="{{ $post->title }}" />
    <meta name="twitter:description" content="{{ $post->description }}" />
    <meta name="twitter:image" content="{{ imageCheck($post->images) }}" />

    <meta name="datePublished" content="{{ $post->created_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="dateModified" content="{{ $post->updated_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="url" content="{{ url()->current() }}">

    <meta name="articleSection" content="news">
    <meta name="articleAuthor" content="{{ $post->author->name }}">


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
@endsection

@section('content')

    @if (adsCheck($ads8->id))
        <div class="container mb-4">
            @if (adsCheck($ads8->id)->type == 1)
                <div class="ad-block">{!! adsCheck($ads8->id)->code !!}</div>
            @else
                <div class="ad-block">
                    <a href="{{ adsCheck($ads8->id)->url }}" title="Reklam {{ $ads8->id }}" class="externallink">
                        <img src="{{ asset('uploads/' . adsCheck($ads8->id)->images) }}" alt="Reklam {{ $ads8->id }}"
                            class="img-fluid lazy" data-type="{{ adsCheck($ads8->id)->type }}"
                            height="{{ adsCheck($ads8->id)->height }}" width="{{ adsCheck($ads8->id)->width }}">
                    </a>
                </div>
            @endif
        </div>
    @endif

    <div class="container" id="infiniteBox">

        <div class="row infiniteContent">

            <div class="detay-sosyal">

                <p>Paylaş</p>

                <!--@if ($magicbox['googlenews'] != null)-->
                <!--    <a href="{{ $magicbox['googlenews'] }}" title="{{ $magicbox['googlenews'] }}"-->
                <!--        class="externallink rsosyal-0"><i class="fa fa-google"></i></a>-->
                <!--@endif-->

                <a href="https://wa.me?text={{ html_entity_decode($post->title) }}@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" title="{{ html_entity_decode($post->title) }} @endif"
                    class="externallink rsosyal-1"><i class="fa fa-whatsapp"></i></a>

                <a href="https://www.facebook.com/sharer/sharer.php?u=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" title="{{ html_entity_decode($post->title) }} @endif"
                    class="externallink rsosyal-2"><i class="fa fa-facebook"></i></a>

                <a href="https://twitter.com/intent/tweet?text={{ html_entity_decode($post->title) }}&url=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
                    title="{{ html_entity_decode($post->title) }}" class="externallink rsosyal-3"><i
                        class="x-corp"></i></a>

                <a href="#yorumlar{{ $post->id }}" title="" class="externallink rsosyal-5"><i
                        class="fa fa-comments"></i></a>

                <!--<a href="" title="" class="externallink rsosyal-4" id="copyDetail"><i-->
                <!--        class="fa fa-copy"></i></a>-->

            </div>

            <div class="col-12 col-lg-9">

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ route('category', ['slug' => $post->category->slug, 'id' => $post->category->id]) }}"
                                class="externallink" title="{{ $post->category->title }}">{{ $post->category->title }}</a>
                        </li>
                        <li class="breadcrumb-item active externallink" aria-current="page">
                            {{ html_entity_decode($post->title) }}</li>
                    </ol>
                </nav>

                <div class="detaybaslik">

                    <h1>{{ html_entity_decode($post->title) }}</h1>

                    <p>{{ html_entity_decode($post->description) }}</p>

                    <span class="nopadli">
                        <img src="{{ asset($post->author->avatar ?? '') }}"
                            onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'"
                            alt="{{ $post->author?->name ?? '' }}" />
                        <b>
                            {{ $post->author?->name ?? 'Admin' }}
                            <b>EDİTÖR</b>
                        </b>
                    </span>

                    <span><i class="fa fa-clock-o"></i>
                        {{ 'Giriş: ' . date('d.m.Y - H:i', strtotime($post->created_at)) }}</span>

                    <span><i class="fa fa-clock-o"></i>
                        {{ 'Güncelleme: ' . date('d.m.Y - H:i', strtotime($post->created_at)) }}</span>

                </div>

                <a data-fancybox="gallery-{{ $post->id }}" href="{{ imageCheck($post->images) }}">
                    <img src="{{ imageCheck($post->images) }}" class="detayresim"
                        alt="{{ html_entity_decode($post->title) }}">
                </a>

                <div class="detail-content mb-4" id="detailContent">

                    @if (adsCheck($ads9->id))
                        @if (adsCheck($ads9->id)->type == 1)
                            <div class="ad-block">{!! adsCheck($ads9->id)->code !!}</div>
                        @else
                            <div class="ad-block">
                                <a href="{{ adsCheck($ads9->id)->url }}" title="Reklam {{ $ads9->id }}"
                                    class="externallink">
                                    <img src="{{ asset('uploads/' . adsCheck($ads9->id)->images) }}"
                                        alt="Reklam {{ $ads9->id }}" class="img-fluid lazy"
                                        data-type="{{ adsCheck($ads9->id)->type }}"
                                        height="{{ adsCheck($ads9->id)->height }}"
                                        width="{{ adsCheck($ads9->id)->width }}">
                                </a>
                            </div>
                        @endif
                    @endif


                    <div style="clear:both;"></div>
                    <div property="articleBody">{!! $post->detail !!}</div>
                    <div style="clear:both;"></div>


                    @if (isset($extra->video_embed) and $extra->video_embed != null)
                        <div class="embed-responsive embed-responsive-16by9">
                            {!! str_replace('class="', 'class="embed-responsive-item ', $extra->video_embed) !!}
                        </div>
                    @endif

                    <div style="clear:both;"></div>

                    @if (isset($extra->related_news) and $extra->related_news > 0)
                        <blockquote cite="#" class="d-flex flex-wrap rounded my-4">
                            <div class="blockquote-image">
                                <a href="{{ route('post', ['categoryslug' => categoryCheck(relatedPostCheck($extra->related_news)->category_id)->slug, 'slug' => relatedPostCheck($extra->related_news)->slug, 'id' => relatedPostCheck($extra->related_news)->id]) }}"
                                    title="{{ relatedPostCheck($extra->related_news)->title }}" class="externallink">
                                    <img src="{{ imageCheck(relatedPostCheck($extra->related_news)->images) }}"
                                        alt="{{ relatedPostCheck($extra->related_news)->title }}" class="img-fluid lazy">
                                </a>
                            </div>
                            <div class="blockquote-content ms-0 ms-md-4 ms-lg-0 ms-xl-4">
                                <div class="blockquote-title">İLİŞKİLİ YAZI</div>
                                <a href="{{ route('post', ['categoryslug' => categoryCheck(relatedPostCheck($extra->related_news)->category_id)->slug, 'slug' => relatedPostCheck($extra->related_news)->slug, 'id' => relatedPostCheck($extra->related_news)->id]) }}"
                                    title="{{ relatedPostCheck($extra->related_news)->title }}" class="externallink">
                                    <h5>{{ relatedPostCheck($extra->related_news)->title }}</h5>
                                </a>
                            </div>
                        </blockquote>
                    @endif

                </div>

                @if (!blank($post->keywords))
                    <div class="detail-tags">
                        @foreach (explode(',', $post->keywords) as $k_item)
                            <a href="{{ route('search.get', ['search' => trim($k_item)]) }}"
                                class="tag-button btn btn-outline-danger keyword-search">
                                #{{ trim($k_item) }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="w-100 mb-2">
                    <div class="d-flex w-100 justify-content-left detail-social-buttons">

                        <!--@if ($magicbox['googlenews'] != null)-->
                        <!--    <div class="social-button mb-4"><a href="{{ $magicbox['googlenews'] }}"-->
                        <!--            title="{{ $magicbox['googlenews'] }}"-->
                        <!--            class="externallink social-link shadow-sm btn-google-news"><i-->
                        <!--                class="google-news"></i></a></div>-->
                        <!--@endif-->

                        <div class="social-button mb-4"><a
                                href="https://wa.me?text={{ html_entity_decode($post->title) }}@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" title="{{ html_entity_decode($post->title) }} @endif"
                                data-action="share/whatsapp/share"
                                class="externallink social-link shadow-sm btn-whatsapp"><i class="whatsapp"></i></a></div>

                        <div class="social-button mb-4"><a
                                href="https://www.facebook.com/sharer/sharer.php?u=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" title="{{ html_entity_decode($post->title) }} @endif"
                                class="externallink social-link shadow-sm btn-facebook"><i class="facebook"></i></a></div>

                        <div class="social-button mb-4"><a
                                href="https://twitter.com/intent/tweet?text={{ html_entity_decode($post->title) }}&url=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
                                title="{{ html_entity_decode($post->title) }}"
                                class="externallink social-link shadow-sm btn-x-corp"><i class="x-corp"></i></a></div>

                        <!--<div class="social-button mb-4"><a href="" title=""-->
                        <!--        class="externallink social-link shadow-sm btn-copy" id="copyDetail"><i-->
                        <!--            class="copy-paste"></i></a></div>-->

                    </div>
                </div>

                <!--Yorumlar-->
                @if ($extra->comment_status == 1)

                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="comment-block shadow-sm p-4 overflow-hidden rounded-1 bg-white">

                                <div class="news-headline-block justify-content-between mb-4"
                                    id="yorumlar{{ $post->id }}">
                                    <h2 class="text-black">BİR CEVAP YAZ</h2>
                                    <div class="headline-block-indicator border-0">
                                        <div class="indicator-ball" style="background-color:#EC0000;"></div>
                                    </div>
                                </div>

                                <p class="comment-desc">E-posta hesabınız yayımlanmayacak. Gerekli alanlar * ile
                                    işaretlenmişlerdir</p>

                                <div class="comment-form">
                                    <form action="{{ route('commentsubmit', ['type' => 0, 'post_id' => $post->id]) }}"
                                        method="post">
                                        @csrf
                                        @if (session('captcha_error'))
                                            <div class="alert alert-danger mb-3" role="alert">
                                                {{ session('captcha_error') }}
                                            </div>
                                        @endif
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
                                        <div class="mb-3">
                                            <div class="g-recaptcha"
                                                data-sitekey="6Lei_SArAAAAAORMajvtoIH5t4CF6Pimt9Oe2FCL">
                                            </div>
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
                                                    <img src="images/user-profile.jpg" alt=""
                                                        class="img-fluid lazy">
                                                </div>
                                                <div class="comment-item-title">
                                                    {{ $comment->title }} <span
                                                        class="comment-date">{{ date('d.m.Y, H:i', strtotime($comment->created_at)) }}</span>
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
                <!--Yorumlar-->

            </div>

            <!--Sağ block-->

            <div class="col-12 col-lg-3">
                <div class="row">

                    @if (adsCheck($ads10->id))
                        <div class="col-12 mb-4">
                            @if (adsCheck($ads10->id)->type == 1)
                                <div class="ad-block">{!! adsCheck($ads10->id)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck($ads10->id)->url }}" title="Reklam {{ $ads10->id }}"
                                        class="externallink">
                                        <img src="{{ asset(adsCheck($ads10->id)->images) }}"
                                            alt="Reklam {{ $ads10->id }}" class="img-fluid lazy"
                                            data-type="{{ adsCheck($ads10->id)->type }}"
                                            height="{{ adsCheck($ads10->id)->height }}"
                                            width="{{ adsCheck($ads10->id)->width }}">
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="col-12 mb-4 mobyok">

                        <div class="spotlar">

                            @php $counter = 1; @endphp

                            @foreach ($hit_news as $hit_n)
                                @if ($counter <= 4)
                                    <div class="spot spotduz spotduztek spotduz-{{ $counter }}">
                                        <a href="{{ route('post', ['categoryslug' => $hit_n->category->slug, 'slug' => $hit_n->slug, 'id' => $hit_n->id]) }}"
                                            title="{{ $hit_n['title'] }}">
                                            <b>{{ categoryCheck($hit_n['category_id'])->title }}</b>
                                            <div class="spot-resim sagdakiler"><img
                                                    src="{{ route('resizeImage', ['i_url' => imageCheck($hit_n['images']), 'w' => 550, 'h' => 307]) }}"
                                                    alt="{{ html_entity_decode($hit_n['title']) }}"
                                                    alt="{{ html_entity_decode($hit_n['title']) }}" /></div>
                                            <p><span>{{ html_entity_decode($hit_n['title']) }}</span></p>
                                        </a>
                                    </div>

                                    @php $counter++; @endphp
                                @endif
                            @endforeach

                        </div>

                    </div>

                </div>
            </div>

            <div style="clear:both;"></div><br />
            <div style="clear:both;"></div>

            <hr style="height:2px;color:#000;background-color:#000;">

            <div style="clear:both;"></div><br />
            <div style="clear:both;"></div>

        </div>

        @if ($infiniteurl != null)
            <a href="{{ route('post', ['categoryslug' => $infiniteurl->category->slug, 'slug' => $infiniteurl->slug, 'id' => $infiniteurl->id]) }}"
                id="gopostinfinite"></a>
        @endif

    </div>

    <div style="clear:both;"></div>

    <style>
        .detail-content * {
            font-family: 'Montserrat', sans-serif !important;
            overflow: hidden;
        }

        .detail-content img,
        .detail-content input {
            width: auto important;
            max-width: 100% important;
            height: auto !important;
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

        .detail-content iframe {
            width: auto important;
            height: 650px !important;
        }

        @media (max-width: 800px) {

            .detail-content iframe {
                width: auto important;
                height: 350px !important;
            }

        }
    </style>

    <link rel="stylesheet" href="{{ asset('v3_public/assets/js/fancybox.css') }}" />
    <script src="{{ asset('v3_public/assets/js/fancybox.umd.js') }}"></script>

@endsection

@section('custom_js')

<script type="text/javascript" src="{{ asset('vendor/js/infinite.js') }}"></script>
        <script>
         let isLoading = false;

        $(window).on('scroll', function () {
            if (isLoading) return;

            const windowWidth = $(window).width();

            let scrollOffset;
            if (windowWidth <= 768) {
                scrollOffset = 1800;
            } else {
                scrollOffset = 1000;
            }

            if ($(window).scrollTop() + $(window).height() + scrollOffset >= $(document).height()) {
                const nextUrl = $('#gopostinfinite').attr('href');
                if (nextUrl) {
                    isLoading = true; // 👈 Scroll kilidi
                    $.get(nextUrl, function (data) {
                        $('#infiniteBox').append($(data).find('.infiniteContent'));

                        // Yeni next URL'yi al
                        const newNext = $(data).find('#gopostinfinite').attr('href');
                        if (newNext) {
                            $('#gopostinfinite').attr('href', newNext);
                        } else {
                            $('#gopostinfinite').remove(); // 👈 Son sayfaysa linki sil
                        }

                        isLoading = false; // 👈 AJAX bittiğinde kilidi aç
                    });
                }
            }
        });


        </script>

@endsection
