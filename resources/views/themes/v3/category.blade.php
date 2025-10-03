@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
    <title>{{ $category->title }} </title>
    <meta name="description" content="{{ $category->description }}">
    <meta name="keywords" content="{{ $category->keywords }}">

    <meta property="og:title" content="{{ $category->title }}" />
    <meta property="og:description" content="{{ $category->description }}" />
    <meta property="og:image" content="{{ imageCheck($category->images) }}" />
    <meta property="og:url" content="{{ route('category', ['slug' => $category->slug, 'id' => $category->id]) }}" />
    <meta property="og:type" content="category" />


    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if (isset($magicbox['tw_name'])) {{ $magicbox['tw_name'] }} @endif" />
    <meta name="twitter:title" content="{{ $category->title }}" />
    <meta name="twitter:description" content="{{ $category->description }}" />
    <meta name="twitter:image" content="{{ imageCheck($category->images) }}" />
    
    <style>
        .ads-area{
            width:100%;
            display:block !important;
            margin:0;
        }
        @media (max-width: 768px) {
             .ads-area{
                margin: 0px 1% 20px 1% !important;
            
            } 
        }
        
        .ads-field{
           
    
        }
        @media (max-width: 768px) {
             .ads-field{
                 width:100%;
            
            } 
        }
    </style>
 
@endsection

@section('content')

    <div class="container">
    @if ($category->show_category_ads == 0)
            @if (adsCheck(12))
                <div class="container mb-4">
                    @if (adsCheck(12)->type == 1)
                        <div class="">{!! adsCheck(12)->code !!}</div>
                    @else
                        <div class="">
                            <a href="{{ adsCheck(12)->url }}" title="Reklam 12" class="externallink">
                                <img src="{{ asset(adsCheck(12)->images) }}"
                                    alt="Reklam 12" class="img-fluid lazy"
                                    data-type="{{ adsCheck(12)->type }}" height="{{ adsCheck(12)->height }}"
                                    width="{{ adsCheck(12)->width }}">
                            </a>
                        </div>
                    @endif
                </div>
    
            @endif
        @endif
        <div class="row">

            <div class="col-12">
                <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">{{ $category->title }} Haberleri</h2>
                    <div class="headline-block-indicator">
                        <div class="indicator-ball" style="background-color:{{ $category->color }}!important;"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 mb-4">

                <div class="headline-block overflow-hidden rounded-1">

                    <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($posts_slider as $key => $post)
                                <a href="{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                                    class="externallink" title="{{ html_entity_decode($post->title) }}">
                                    <div class="carousel-item @if ($key == 0) active @endif">
                                        <div class="headline-item">

                                            <div class="headline-image"><img width="100%"
                                                    src="{{ route('resizeImage', ['i_url' => imageCheck($post['images']), 'w' => 777, 'h' => 510]) }}"
                                                    alt="{{ html_entity_decode($post['title']) }}" class="lazy"></div>

                                            <div class="headline-title">
                                                @if ($post->show_title_slide == 0)
                                                    @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                                                        <h1>{{ html_entity_decode($post->title) }}</h1>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @if ($key == 14)
                                    @break
                                @endif
                                <button class="carousel-control-prev d-flex" type="button">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"
                                        data-bs-target="#headlineCarousel" data-bs-slide="prev"></span>
                                    <span class="visually-hidden">Önceki</span>
                                </button>
    
                                <button class="carousel-control-next d-flex" type="button">
                                    <span class="carousel-control-next-icon" aria-hidden="true"
                                        data-bs-target="#headlineCarousel" data-bs-slide="next"></span>
                                    <span class="visually-hidden">Sonraki</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="carousel-indicators">
                            @foreach ($posts_slider as $key => $post)
                                <button type="button"
                                    onclick="window.location.href='{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}'"
                                    data-bs-target="#headlineCarousel" data-bs-slide-to="{{ $key }}"
                                    @if ($key == 0) class="active" aria-current="true" @endif>{{ $key + 1 }}</button>
                                @if ($key == 14)
                                    @break
                                @endif
                   
                   
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-12 col-md-4">



                <div class="spotlar">

                    @php $counter = 1; @endphp

                    @if (count($hit_popups) > 0)
                        @foreach ($hit_popups as $hit_popup)
                            @if ($counter <= 2)
                                <div class="spot spotduz spotduztek spotduz-{{ $counter }}">
                                    <a href="{{ route('post', ['categoryslug' => $hit_popup->category->slug, 'slug' => $hit_popup->slug, 'id' => $hit_popup->id]) }}"
                                        title="{{ $hit_popup['title'] }}">
                                        <b>{{ categoryCheck($hit_popup['category_id'])->title }}</b>
                                        <div class="spot-resim"><img
                                                src="{{ route('resizeImage', ['i_url' => imageCheck($hit_popup['images']), 'w' => 550, 'h' => 307]) }}"
                                                alt="{{ html_entity_decode($hit_popup['title']) }}"
                                                alt="{{ html_entity_decode($hit_popup['title']) }}" /></div>
                                        <p><span>{{ html_entity_decode($hit_popup['title']) }}</span></p>
                                    </a>
                                </div>

                                @php $counter++; @endphp
                            @endif
                        @endforeach
                    @endif

                </div>

            </div>

        </div>

    <div style="clear:both;"></div>

    <!-- Banner 06 -->
    @if ($category->show_category_ads == 0)
        @if (adsCheck(11))
            <div class="container mb-4">
                @if (adsCheck(11)->type == 1)
                    <div class="">{!! adsCheck(11)->code !!}</div>
                @else
                    <div class="">
                        <a href="{{ adsCheck(11)->url }}" title="Reklam 11" class="externallink">
                            <img src="{{ asset(adsCheck(11)->images) }}"
                                alt="Reklam 11" class="img-fluid lazy"
                                data-type="{{ adsCheck(11)->type }}" height="{{ adsCheck(11)->height }}"
                                width="{{ adsCheck(11)->width }}">
                        </a>
                    </div>
                @endif
            </div>

        @endif
    @endif

    <!-- News (Haberler) -->
    <div class="container">
        <div class="spotlar">

            <div class="col-12">
                <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">Diğer {{ $category->title }} Haberleri</h2>
                    <div class="headline-block-indicator">
                        <div class="indicator-ball" style="background-color:#EC0000;"></div>
                    </div>
                </div>
            </div>

            <div class="spotlar" id="newsContainer">
                @include('themes.' . $theme . '.main._posts_list', ['posts_other' => $posts_other])
            </div>
                
            <div id="loader" style="display: none; text-align: center;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>


            <div style="clear:both;"></div>

        

        </div>
    </div>

    <div style="clear:both;"></div>

@endsection

@section('custom_js')
<script>
    let page = 2; // İlk sayfa yüklendi
    let loading = false;
    let nextPage = true;

     window.onscroll = function () {
            const scrollPosition = window.innerHeight + window.scrollY;
            const documentHeight = document.body.offsetHeight;
            
            const isMobile = window.innerWidth <= 768; // mobil için breakpoint
            const offsetThreshold = isMobile ? 1500 : 500; // mobil için 300px, desktop için 500px
              console.log(offsetThreshold)
            if (scrollPosition >= documentHeight - offsetThreshold && !loading && nextPage) {
                loadMoreData();
            }
    };

    function loadMoreData() {
        loading = true;
        document.getElementById("loader").style.display = "block";

        fetch(`{{ route('category.loadMore', ['slug' => $category->slug]) }}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.html.trim() !== '') {
                    document.getElementById("newsContainer").insertAdjacentHTML('beforeend', data.html);
                    page++;
                    nextPage = data.nextPage !== null;
                } else {
                    nextPage = false;
                }
                loading = false;
                document.getElementById("loader").style.display = "none";
            })
            .catch(error => {
                console.error("Hata:", error);
                loading = false;
                document.getElementById("loader").style.display = "none";
            });
    }
</script>
@endsection
