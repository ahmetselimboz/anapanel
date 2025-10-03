@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
    <title>{{ $post->title }}</title>
    <meta name="title" content="{{ $post->title }}">
    <meta name="description" content="{{ $post->description }}">
    <meta name="keywords" content="{{ $post->keywords }}">

    <link rel="canonical"
        href="{{ route('amppost', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}" />
    <meta name="datePublished" content="{{ $post->created_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="dateModified" content="{{ $post->updated_at->format('Y-m-d\TH:i:sP') }}">
    <meta name="url" content="{{ url()->current() }}">

    <meta name="articleSection" content="news">
    <meta name="articleAuthor" content="{{ $post->author->name ?? "" }}">

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

    <script type="application/ld+json">
                            {
                              "@context": "https://schema.org",
                              "@type": "NewsArticle",
                              "mainEntityOfPage": {
                                "@type": "WebPage",
                                "@id": "@if($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
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
                                "item": "{{ route('category', ['slug' => $post->category->slug, 'id' => $post->category->id]) }}"
                              },{
                                "@type": "ListItem",
                                "position": 2,
                                "name": "{{ html_entity_decode($post->title) }}",
                                "item": "@if($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
                              }]
                            }
                            </script>

    <style>
        .tag-button {
            color: #000 !important;
            background-color: RGBA(var(--bs-light-rgb), var(--bs-bg-opacity, 1)) !important;
        }

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
    @php
        $url = route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]);
        $title = $post->title;
        $whatsAppText = urlencode("{$title}\n\n{$url}");
    @endphp

    @if (isset($extra->show_post_ads) and $extra->show_post_ads == 0)
        @if (adsCheck(8) && adsCheck(8)->publish == 0)
            <div class="container mb-4">
                @if (adsCheck(8)->type == 1)
                    <div class="ad-block">{!! adsCheck(8)->code !!}</div>
                @else
                    <div class="ad-block">
                        <a href="{{ adsCheck(8)->url }}" title="Reklam 8" class="externallink">
                            <img src="{{ asset(adsCheck(8)->images) }}" alt="Reklam 8" class="img-fluid lazy"
                                data-type="{{ adsCheck(8)->type }}" height="{{ adsCheck(8)->height }}" width="{{ adsCheck(8)->width }}">
                        </a>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <div class="container" id="infiniteBox">
        <div class="row infiniteContent">

            <div class="detay-social-buttons">

                @if ($magicbox['googlenews'] != null)
                    <div class="social-button"><a href="{{ $magicbox['googlenews'] }}" title="{{ $magicbox['googlenews'] }}"
                            class="externallink social-link shadow-sm "><i class="google-news"></i></a></div>
                @endif


                <div class="social-button">
                    <a href="https://wa.me/?text={{ $whatsAppText }}" data-action="share/whatsapp/share"
                        title="WhatsApp ile Paylaş" class="externallink social-link shadow-sm btn-whatsapp">
                        <i class="whatsapp"></i>
                    </a>
                </div>
                <div class="social-button"><a
                        href="https://www.facebook.com/sharer/sharer.php?u= {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                        title="{{ html_entity_decode($post->title) }}"
                        class="externallink social-link shadow-sm btn-facebook"><i class="facebook"></i></a></div>
                <div class="social-button"><a
                        href="https://twitter.com/intent/tweet?text={{ html_entity_decode($post->title) }}&url={{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} "
                        title="{{ html_entity_decode($post->title) }}"
                        class="externallink social-link shadow-sm btn-x-corp"><i class="x-corp"></i></a></div>
                <div class="social-button"><a href="" title="" class="externallink social-link shadow-sm btn-copy"
                        id="copyDetail"><i class="copy-paste"></i></a>
                </div>
                <div class="social-button">
                    <a href="#yorumlar" title="" class="social-link shadow-sm comments-btn-area">
                        <i class="fa fa-comments comments-btn" style="font-size: 20px;"></i>
                    </a>
                </div>

            </div>

            <div class="col-12 col-lg-8">

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ route('category', ['slug' => $post->category->slug, 'id' => $post->category->id]) }}"
                                class="externallink" title="{{ $post->category->title }}">{{ $post->category->title }}</a>
                        </li>
                        <li class="breadcrumb-item active externallink" aria-current="page">
                            {{ html_entity_decode($post->title) }}
                        </li>
                    </ol>
                </nav>

                <h1 class="detaybaslik2">{{ html_entity_decode($post->title) }}</h1>

                <div class="col-12  d-flex align-items-center d-md-none mt-3 mt-md-0">
                    <div class="d-flex d-md-none col-6">

                        <div class="social-button">
                            <a href="https://wa.me/?text={{ $whatsAppText }}" data-action="share/whatsapp/share"
                                title="WhatsApp ile Paylaş"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-whatsapp">
                                <i class="whatsapp"></i>
                            </a>
                        </div>
                        <div class="social-button">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                            title="{{ html_entity_decode($post->title) }} @endif"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-facebook">
                                <i class="facebook"></i>
                            </a>
                        </div>
                        <div class="social-button">
                            <a href="https://twitter.com/intent/tweet?text={{ html_entity_decode($post->title) }}&url=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
                                title="{{ html_entity_decode($post->title) }}"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-x-corp"><i
                                    class="x-corp"></i>
                            </a>
                        </div>
                        <div class="social-button">
                            <a href="#yorumlar" title="" class="social-link shadow-sm comments-btn-area">
                                <i class="fa fa-comments comments-btn" style="font-size: 20px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div style="clear:both;"></div>
                <hr class="mb-4">
                <div style="clear:both;"></div>

                <div class="detaybaslik">

                    <p class="detail-spot mb-3" style="font-size: 16px!important;">
                        {{ html_entity_decode($post->description) }}
                    </p>

                </div>

                <div class="row pb-2 pt-3 ">
                    <div class="col-12 d-flex flex-row align-items-center flex-nowrap" >
                            <div class="author-info border-end border-secondary pe-3" style="--bs-border-opacity: .3;">
                                <div class="author-avatar">
                                    <img src="{{ asset( $post->author->avatar ?? '') }}" onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="{{ $post->author?->name ?? ''}}"
                                        class="img-fluid">
                                </div>
                                <div>
                                    <h2 class="author-name mb-1">{{ $post->author?->name ?? 'Admin'}}</h2>
                                    <p class="author-title">EDİTÖR</p>
                                </div>
                            </div>


                        <div class="d-flex flex-column align-items-center justify-content-center border-end border-secondary px-3" style="--bs-border-opacity: .3;">
                            <p class="publish-info my-1">{{ date('d.m.Y - H:i', strtotime($post->created_at)) }}</p>
                            <span class="reading-info mb-1">YAYINLANMA</span>
                        </div>
                        @if ($post->updated_at)
                        <div class="d-flex flex-column align-items-center justify-content-center border-end border-secondary px-3" style="--bs-border-opacity: .3;">
                            <p class="publish-info my-1">{{ date('d.m.Y - H:i', strtotime($post->updated_at)) }}</p>
                            <span class="reading-info mb-1">GÜNCELLEME</span>
                        </div>
                        @endif
                    </div>
                </div>

                <img src="{{ imageCheck($post->images) }}" class="detayresim" alt="{{ html_entity_decode($post->title) }}">

                <div class="detail-content mb-4" id="detailContent">

                    @if (isset($extra->show_post_ads) and $extra->show_post_ads == 0)
                        @if (adsCheck(9) && adsCheck(9)->publish == 0)
                            @if (adsCheck(9)->type == 1)
                                <div class="ad-block">{!! adsCheck(9)->code !!}</div>
                            @else
                                <div class="ad-block">
                                    <a href="{{ adsCheck(9)->url }}" title="Reklam {{ $ads9->id }}" class="externallink">
                                        <img src="{{ asset(adsCheck(9)->images) }}" alt="Reklam 9" class="img-fluid lazy"
                                            data-type="{{ adsCheck(9)->type }}" height="{{ adsCheck(9)->height }}"
                                            width="{{ adsCheck(9)->width }}">
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif

                    @if (isset($extra->video_embed) and $extra->video_embed != null)
                        <div class="embed-responsive embed-responsive-16by9">
                            {!! str_replace('class="', 'class="embed-responsive-item ', $extra->video_embed) !!}
                        </div>
                    @endif


                    <div class="icerikalani" property="articleBody">{!! $post->detail !!}</div>

                    @if (isset($extra->related_news) and $extra->related_news > 0)
                        <blockquote cite="#" class="d-flex flex-wrap rounded my-4">
                            <div class="blockquote-image">
                                <a href="{{ route('post', ['categoryslug' => categoryCheck(relatedPostCheck($extra->related_news)->category_id)->slug, 'slug' => relatedPostCheck($extra->related_news)->slug, 'id' => relatedPostCheck($extra->related_news)->id]) }}"
                                    title="{{ relatedPostCheck($extra->related_news)->title }}" class="externallink">
                                    <img width="100" src="{{ imageCheck(relatedPostCheck($extra->related_news)->images) }}"
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
                @if (isset($extra->news_source) && !blank($extra->news_source))
                <div class="detail-tags py-3 small border-top ">
                    <span class="source-name pe-3 text-uppercase"><strong class="text-uppercase">Kaynak: </strong>{{ $extra->news_source}}</span>
                </div>
                @endif
                @if (!blank($post->keywords))
                    <div class="detail-tags">
                        @foreach (explode(',', $post->keywords) as $k_item)
                            <a href="{{ route('search.get', ['search' => trim($k_item)]) }}"
                                 class="tag-button externallink btn btn-outline-danger keyword-search">
                                 #{{ trim($k_item) }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="w-100 mb-2 mb-md-4 d-block d-md-flex align-items-center">
                    <div class="d-flex justify-content-left detail-social-buttons col-12 col-md-6">
                        @if ($magicbox['googlenews'] != null)
                            <div class="social-button mb-4 mb-md-0"><a href="{{ $magicbox['googlenews'] }}"
                                    title="{{ $magicbox['googlenews'] }}" class="externallink social-link shadow-sm"><i
                                        class="google-news"></i></a></div>
                        @endif
                        <div class="social-button mb-4 mb-md-0">
                            <a href="https://wa.me/?text={{ $whatsAppText }}" data-action="share/whatsapp/share"
                                title="WhatsApp ile Paylaş"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-whatsapp">
                                <i class="whatsapp"></i>
                            </a>
                        </div>
                        <div class="social-button mb-4 mb-md-0">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                            title="{{ html_entity_decode($post->title) }} @endif"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-facebook">
                                <i class="facebook"></i>
                            </a>
                        </div>
                        <div class="social-button mb-4 mb-md-0">
                            <a href="https://twitter.com/intent/tweet?text={{ html_entity_decode($post->title) }}&url=@if ($post->category != null) {{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }} @endif"
                                title="{{ html_entity_decode($post->title) }}"
                                class="externallink social-link social-button-news-bottom shadow-sm btn-x-corp"><i
                                    class="x-corp"></i>
                            </a>
                        </div>
                        <div class="social-button mb-4 mb-md-0">
                            <a href="#yorumlar" title="" class="social-link shadow-sm comments-btn-area">
                                <i class="fa fa-comments comments-btn" style="font-size: 20px;"></i>
                            </a>
                        </div>
                        <div class="social-button mb-4 mb-md-0  position-relative">
                            <button type="button" title=""
                                class="shadow-sm options-btn-area d-none d-md-flex w-auto h-auto">
                                <i class="fa fa-ellipsis-h  options-btn" style="font-size: 20px;"></i>
                                Seçenekler

                            </button>
                            <button type="button" title=""
                                class="shadow-sm options-btn-area d-flex d-md-none rounded-circle p-0">
                                <i class="fa fa-ellipsis-h  options-btn m-0" style="font-size: 20px;"></i>


                            </button>
                            <div class="options-card d-none">

                                <a href="#yorumlar" class="">
                                    <i class="fa fa-comments" style="font-size: 20px;"></i>
                                    Yorum Yap
                                </a>
                                <a href="{{ $magicbox["googlenews"] }}" class="" target="_blank">
                                    <i class="fa fa-newspaper-o " style="font-size: 20px;"></i>
                                    Google News
                                </a>
                                <a href="/" class="">
                                    <i class="fa fa-home" style="font-size: 20px;"></i>
                                    Anasayfa
                                </a>
                            </div>


                        </div>

                    </div>

                </div>
                {{-- @endif --}}

                @if ($extra->comment_status == 0)
                    <div class="row" id="yorumlar"><!--Yorumlar-->
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
                                    <form action="{{ route('commentsubmit', ['type' => 0, 'post_id' => $post->id]) }}"
                                        method="post">
                                        @csrf
                                        @if(session('captcha_error'))
                                            <div class="alert alert-danger mb-3" role="alert">
                                                {{ session('captcha_error') }}
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <textarea name="detail" class="form-control" id="commentMessage"
                                                aria-describedby="commentMessage" placeholder="Yorumunuz *"></textarea>
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
                                            @if($recaptcha_site_key)
                                                <div class="g-recaptcha" data-sitekey="{{ $recaptcha_site_key }}"></div>
                                            @endif
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
                                                    <img src="images/user-profile.jpg" alt="" class="img-fluid lazy">
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
            </div>



            <!--Sağ block-->

            <div class="col-12 col-lg-4">
                <div class="row">

                    @if (isset($extra->show_post_ads) and $extra->show_post_ads == 0)
                        @if (adsCheck(10) && adsCheck(10)->publish == 0)
                            <div class="col-12 mb-4">
                                @if (adsCheck(10)->type == 1)
                                    <div class="ad-block">{!! adsCheck(10)->code !!}</div>
                                @else
                                    <div class="ad-block">
                                        <a href="{{ adsCheck(10)->url }}" title="Reklam {{ 10 }}" class="externallink">
                                            <img src="{{ asset('uploads/' . adsCheck(10)->images) }}" alt="Reklam 10"
                                                class="img-fluid lazy" data-type="{{ adsCheck(10)->type }}"
                                                height="{{ adsCheck(10)->height }}" width="{{ adsCheck(10)->width }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif



                    <div class="col-12 mb-4">

                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm mb-4">

                            <h2 class="mostly-block-headline mb-4" style="background-color:#ef0000;">En Çok Okunanlar</h2>

                            <div class="container-fluid">


                                @foreach ($hit_news as $hit_n)
                                    @if ($loop->iteration <= 4)
                                        <div class="tekli">
                                            <a href="{{ route('post', ['categoryslug' => $hit_n->category->slug, 'slug' => $hit_n->slug, 'id' => $hit_n->id]) }}"
                                                title="{{ html_entity_decode($hit_n->title) }}">
                                                <i class="fa fa-dot-circle-o"></i>
                                                <p>
                                                    <b>{{ date('H:i', strtotime($hit_n->created_at)) }}</b>
                                                    <span>{{ html_entity_decode($hit_n->title) }}</span>
                                                </p>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        </div>

                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm d-none d-lg-block">

                            <h2 class="mostly-block-headline mb-4" style="background-color:#ef0000;">İlginizi Çekebilir
                            </h2>

                            <div class="container-fluid">


                                @foreach ($hit_news as $hit_n)
                                    @if ($loop->iteration > 4 && $loop->iteration <= 7)
                                        <div class="spot-list spot-list2">
                                            <a href="{{ route('post', ['categoryslug' => $hit_n->category->slug, 'slug' => $hit_n->slug, 'id' => $hit_n->id]) }}"
                                                title="{{ html_entity_decode($hit_n->title) }}">
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($hit_n->images), 'w' => 142, 'h' => 95]) }}"
                                                    alt="{{ html_entity_decode($hit_n->title) }}" />
                                                <p><span>{{ html_entity_decode($hit_n->title) }}</span></p>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">Benzer Haberler</h2>
                    <div class="headline-block-indicator">
                        <div class="indicator-ball" style="background-color:#EC0000;"></div>
                    </div>
                </div>
            </div>

            @if (count($same_post) > 0)
                    <div class="spotlar d-none d-lg-block">



                        @php
                            $postsame = [];
                            if (!blank($same_post)) {
                                $count = count((array) $same_post);
                                if ($count < 4) {
                                    $postsame = $same_post;
                                } else {
                                    $postsame = $same_post->random(4);
                                }
                            }
                        @endphp

                        @foreach ($postsame as $spost)
                            <div class="spot-2">
                                <a href="{{ route('post', ['categoryslug' => $spost->category->slug, 'slug' => $spost->slug, 'id' => $spost->id]) }}"
                                    title="{{ html_entity_decode($spost->title) }}">
                                    <div class="w-100 overflow-hidden" style="height: 276px">
                                        <img src="{{ route('resizeImage', ['i_url' => imageCheck($spost->images), 'w' => 217, 'h' => 250]) }}"
                                            alt="{{ html_entity_decode($spost->title) }}" style="height: auto" />
                                    </div>

                                    <p><span>{{ html_entity_decode($spost->title) }}</span></p>
                                </a>
                            </div>
                        @endforeach

                    </div>
                    <div style="clear:both;"></div>
                    <br /><br /><br />
                    <div style="clear:both;"></div>
            @endif

        </div>

        @if ($infiniteurl != null)
            <a href="{{ route('post', ['categoryslug' => $infiniteurl->category->slug, 'slug' => $infiniteurl->slug, 'id' => $infiniteurl->id]) }}"
                id="gopostinfinite">next</a>
        @endif

    </div>

    <div style="clear:both;"></div>

@endsection

@section('custom_js')
    @if ($infiniteurl != null)
        <script type="text/javascript" src="{{ asset('/vendor/js/infinite.js') }}"></script>
        <script>
            $('#infiniteBox').cleverInfiniteScroll({
                contentsWrapperSelector: '#infiniteBox',
                contentSelector: '.infiniteContent',
                nextSelector: '#gopostinfinite'
            });


        </script>
    @endif

    <script>
        $(document).ready(function () {
            $(".options-btn-area").click(function () {
                $(".options-card").toggleClass("d-none");
            })
        });
    </script>
@endsection
