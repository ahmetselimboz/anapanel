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
            <meta name="twitter:site" content="@if (isset($magicbox[' tw_name'])) {{ $magicbox['tw_name'] }} @endif" />
            <meta name="twitter:title" content="{{ $category->title }}" />
            <meta name="twitter:description" content="{{ $category->description }}" />
            <meta name="twitter:image" content="{{ imageCheck($category->images) }}" />
        @endsection

        @section('content')

            <div class="container">

                <div class="row">

                    <div class="col-12">
                        <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                            <h2 class="text-black">{{ $category->title }} Haberleri</h2>
                            <div class="headline-block-indicator">
                                <div class="indicator-ball" style="background-color:{{ $category->color }}!important;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-8">

                        <div class="headline-block bg-white overflow-hidden rounded-1 shadow-sm mb-4">

                            <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                                <div class="carousel-inner">

                                    @foreach ($posts_slider as $key => $post)
                                        <a href="{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                                            class="externallink" title="{{ html_entity_decode($post->title) }}">
                                            <div class="carousel-item @if ($key == 0) active @endif">
                                                <div class="headline-item">
                                                    <div class="headline-image">
                                                        <img width="100%"
                                                            src="{{ route('resizeImage', ['i_url' => imageCheck($post->images), 'w' => 777, 'h' => 510]) }}"
                                                            class="lazy">
                                                    </div>
                                                    <div class="headline-title bg-dark-gradiant">
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
                                    @endforeach
                                    <button class="carousel-control-prev d-flex" type="button"
                                        data-bs-target="#headlineCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Önceki</span>
                                    </button>

                                    <button class="carousel-control-next d-flex" type="button"
                                        data-bs-target="#headlineCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Sonraki</span>
                                    </button>
                                </div>

                                <div class="carousel-indicators">
                                    @foreach ($posts_slider as $key => $post)
                                        <button type="button" data-bs-target="#headlineCarousel"
                                            data-bs-slide-to="{{ $key }}"
                                            @if ($key == 0) class="active" aria-current="true" @endif>

                                            <!-- Masaüstünde sayı, mobilde nokta -->
                                            <span class="d-none d-md-inline">{{ $key + 1 }}</span>
                                            <span class="d-inline d-md-none">●</span>

                                        </button>
                                        @if ($key == 14)
                                            @break
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-md-4">


                        <div class="row">

                            <div class="col-12 mb-4">
                                <div class="mostly-block overflow-hidden rounded-1 shadow-sm">

                                    <h2 class="mostly-block-headline mb-4" style="background-color:#ef0000;">En Çok
                                        Okunanlar</h2>

                                    <div class="container-fluid">

                                        @php
                                            $counter = 1;
                                        @endphp
                                        @if (count($hit_popups) > 0)
                                            @foreach ($hit_popups as $hit_popup)
                                                @if ($counter <= 5)
                                                    <div class="tekli">
                                                        <a href="{{ route('post', ['categoryslug' => $hit_popup->category->slug, 'slug' => $hit_popup->slug, 'id' => $hit_popup->id]) }}"
                                                            title="{{ html_entity_decode($hit_popup->title) }}">
                                                            <i class="fa fa-dot-circle-o"></i>
                                                            <p>
                                                                <b>Okumadan Geçme</b>
                                                                <span>{{ html_entity_decode($hit_popup->title) }}</span>
                                                            </p>
                                                        </a>
                                                    </div>

                                                    @php
                                                        $counter++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div style="clear:both;"></div>

                <div class="spotlar">
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($posts_slider as $key => $post)
                        @if ($key > 14)
                            <div class="spot-2">
                                <a href="{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                                    title="{{ html_entity_decode($post->title) }}">
                                    <img src="{{ route('resizeImage', ['i_url' => imageCheck($post->images), 'w' => 216, 'h' => 250]) }}"
                                        alt="{{ html_entity_decode($post->title) }}" />
                                    <p><span>{{ html_entity_decode($post->title) }}</span></p>
                                </a>
                            </div>

                            @php
                                $counter++;
                            @endphp
                        @endif
                    @endforeach
                </div>

            </div>

            <div style="clear:both;"></div>

            <!-- Banner 06 -->


            <!-- News (Haberler) -->
            <div class="container">
                <div class="spotlar">

                    <div class="row">

                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                <h2 class="text-black">Diğer {{ $category->title }} Haberleri</h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball" style="background-color:#EC0000;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-12">


                            @php
                                $key = 1;
                            @endphp

                            @foreach ($posts_other as $post)
                                @php
                                    $showPost = true; // Haber gösterilecek varsayımıyla başla

                                    // Eğer reklam aktifse haberi gösterme
                                    if (
                                        $loop->iteration == 2 &&
                                        adsCheck($ads6->id) &&
                                        adsCheck($ads6->id)->publish == 0
                                    ) {
                                        $showPost = false;
                                    }

                                    if (
                                        ($loop->iteration == 5 || $loop->iteration == 11) &&
                                        adsCheck($ads7->id) &&
                                        adsCheck($ads7->id)->publish == 0
                                    ) {
                                        $showPost = false;
                                    }
                                @endphp

                                @if ($showPost)
                                    <div class="spot spotduz spotduz-{{ $key }}">
                                        <a href="{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                                            title="{{ html_entity_decode($post->title) }}">
                                            <img src="{{ route('resizeImage', ['i_url' => imageCheck($post->images), 'w' => 380, 'h' => 253]) }}"
                                                alt="{{ html_entity_decode($post->title) }}" />
                                            <p><span>{{ html_entity_decode($post->title) }}</span></p>
                                        </a>
                                    </div>
                                    @php $key++; @endphp
                                @endif

                                {{-- Reklam gösterme mantığı --}}
                                @if (
                                    $loop->iteration == 2 &&
                                        $category->show_category_ads == 0 &&
                                        adsCheck($ads6->id) &&
                                        adsCheck($ads6->id)->publish == 0)
                                    <div class="spot spotduz spotduz-{{ $key }}">
                                        @if (adsCheck($ads6->id)->type == 1)
                                            {!! adsCheck($ads6->id)->code !!}
                                        @else
                                            <a href="{{ adsCheck($ads6->id)->url }}" title="Reklam {{ $ads6->id }}"
                                                class="externallink">
                                                <img src="{{ asset('uploads/' . adsCheck($ads6->id)->images) }}"
                                                    alt="Reklam {{ $ads6->id }}" class="img-fluid lazy"
                                                    data-type="{{ adsCheck($ads6->id)->type }}"
                                                    height="{{ adsCheck($ads6->id)->height }}"
                                                    width="{{ adsCheck($ads6->id)->width }}">
                                            </a>
                                        @endif
                                    </div>
                                    @php $key++; @endphp
                                @endif

                                @if (
                                    ($loop->iteration == 5 || $loop->iteration == 11) &&
                                        $category->show_category_ads == 0 &&
                                        adsCheck($ads7->id) &&
                                        adsCheck($ads7->id)->publish == 0)
                                    <div class="spot spotduz spotduz-{{ $key }}">
                                        @if (adsCheck($ads7->id)->type == 1)
                                            {!! adsCheck($ads7->id)->code !!}
                                        @else
                                            <a href="{{ adsCheck($ads7->id)->url }}" title="Reklam {{ $ads7->id }}"
                                                class="externallink">
                                                <img src="{{ asset('uploads/' . adsCheck($ads7->id)->images) }}"
                                                    alt="Reklam {{ $ads7->id }}" class="img-fluid lazy"
                                                    data-type="{{ adsCheck($ads7->id)->type }}"
                                                    height="{{ adsCheck($ads7->id)->height }}"
                                                    width="{{ adsCheck($ads7->id)->width }}">
                                            </a>
                                        @endif
                                    </div>
                                    @php $key++; @endphp
                                @endif
                            @endforeach

                        </div>



                    </div>


                    {{ $posts_other->links() }}

                </div>
            </div>

            <div style="clear:both;"></div>

        @endsection
