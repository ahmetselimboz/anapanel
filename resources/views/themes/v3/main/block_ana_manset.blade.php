<div class="container">
    <div class="row">

        <div class="col-12 col-lg-8 mb-2">

            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))

                <div class="headline-block overflow-hidden rounded-1">
                    <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                        <div class="carousel-inner">

                            @php $sayim = 0; @endphp
                            @php $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json'); @endphp

                            @foreach ($ana_mansetler as $manset_key => $ana_manset)
                                @if ($magicbox['mansetsabitreklamno'] != null and $ads21 != null and adsCheck($ads21->id))
                                    @if ($sayim > 15)
                                        @break
                                    @endif
                                @else
                                    @if ($sayim > 17)
                                        @break
                                    @endif
                                @endif

                                @php $sayim++; @endphp

                                @if ($manset_key != $magicbox['mansetsabitreklamno'] - 1)
                                    <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                                        class="externallink" title="{{ html_entity_decode($ana_manset['title']) }}">
                                        <div class="carousel-item @if ($manset_key == 0) active @endif ">
                                            <div class="headline-item">

                                                <div class="headline-image"><img width="100%"
                                                        src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                                                        alt="{{ html_entity_decode($ana_manset['title']) }}"
                                                        class="lazy"></div>

                                                <div class="headline-title">
                                                    @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                                                        @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                                                            <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                                                            </h1>
                                                        @endif
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                @else
                                    @if ($magicbox['mansetsabitreklamno'] != null and $ads21 != null and adsCheck($ads21->id))
                                        @php $adsR = $manset_key; @endphp

                                        @if (adsCheck($ads21->id)->type == 1)
                                            {!! adsCheck($ads21->id)->code !!}
                                        @else
                                            <a href="{{ adsCheck($ads21->id)->url }}" class="externallink"
                                                title="Reklam {{ $ads21->id }}">
                                                <div
                                                    class="carousel-item @if ($manset_key == 0) active @endif ">
                                                    <div class="headline-item">
                                                        <div class="headline-image"><img class="lazy"
                                                                src="{{ adsCheck($ads21->id)->images }}"
                                                                alt="Reklam {{ $ads21->id }}"
                                                                data-type="{{ adsCheck($ads21->id)->type }}"
                                                                height="{{ adsCheck($ads21->id)->height }}"
                                                                width="{{ adsCheck($ads21->id)->width }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endif
                                    @else
                                        <!-- Tekrar Manşet haberi çevir -->

                                        <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                                            class="externallink"
                                            title="{{ html_entity_decode($ana_manset['title']) }}">
                                            <div
                                                class="carousel-item @if ($manset_key == 0) active @endif ">
                                                <div class="headline-item">

                                                    <div class="headline-image"><img width="100%"
                                                            src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                                                            alt="{{ html_entity_decode($ana_manset['title']) }}"
                                                            class="lazy"></div>

                                                    <div class="headline-title bg-dark-gradiant">
                                                        @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                                                            @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                                                                <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                                                                </h1>
                                                            @endif
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            @endforeach
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
                        </div>

                        <div class="carousel-indicators">
                            @php $sayim = 1; @endphp

                            @foreach ($ana_mansetler as $manset_key_number => $ana_manset)
                                @if ($magicbox['mansetsabitreklamno'] != null and $ads21 != null and adsCheck($ads21->id))
                                    @if ($sayim > 15)
                                        @break
                                    @endif
                                @else
                                    @if ($sayim >= 16)
                                        @break
                                    @endif
                                @endif


                                <button type="button" data-bs-config="{'delay':0}" data-bs-target="#headlineCarousel"
                                    data-bs-slide-to="{{ $manset_key_number }}"
                                    @if ($manset_key_number == 0) class="active" aria-current="true" @endif
                                    onclick="window.location.href='{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}'">

                                    @if (isset($adsR) and $adsR == $manset_key_number)
                                        R
                                        @php $sayim--; @endphp
                                    @else
                                        {{ $sayim }}
                                    @endif
                                </button>

                                @php $sayim++; @endphp
                            @endforeach
                        </div>

                    </div>
                </div>
            @else
                <div class="alert alert-warning"> Ana Manşet Bulunamadı</div>

            @endif

        </div>

        <div class="col-12 col-lg-4 mb-2 d-md-block d-none">
            <div class="spotlar">

                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json'))
                    @php $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp

                    @php $counter = 1; @endphp

                    @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                        <div class="spot spotduz spotduztek spotduz-{{ $counter }}">
                            <a href="{{ route('post', ['categoryslug' => categoryCheck($mini_manset['category_id'])->slug, 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                title="{{ $mini_manset['title'] }}">
                                <b>{{ categoryCheck($mini_manset['category_id'])->title }}</b>
                                <div class="spot-resim"><img
                                        src="{{ route('resizeImage', ['i_url' => imageCheck($mini_manset['images']), 'w' => 550, 'h' => 307]) }}"
                                        alt="{{ html_entity_decode($mini_manset['title']) }}"
                                        alt="{{ html_entity_decode($mini_manset['title']) }}" /></div>
                                <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                            </a>
                        </div>

                        @php $counter++; @endphp

                        @if ($minimanset_key == 1)
                            @break
                        @endif
                    @endforeach
                @else
                    <div class="alert alert-warning"> Mini Manşet Bulunamadı</div>
                @endif

            </div>
        </div>

    </div>
</div>

<div class="tmz"></div>

@php
    $keyword1 = isset($magicbox['keyword1']) ? $magicbox['keyword1'] : '';
    $keyword2 = isset($magicbox['keyword2']) ? $magicbox['keyword2'] : '';
    $keyword3 = isset($magicbox['keyword3']) ? $magicbox['keyword3'] : '';
    $keyword4 = isset($magicbox['keyword4']) ? $magicbox['keyword4'] : '';
    $keyword5 = isset($magicbox['keyword5']) ? $magicbox['keyword5'] : '';
    $keyword6 = isset($magicbox['keyword6']) ? $magicbox['keyword6'] : '';
@endphp

@if (
    !blank($keyword1) ||
        !blank($keyword2) ||
        !blank($keyword3) ||
        !blank($keyword4) ||
        !blank($keyword5) ||
        !blank($keyword6))
    <style>
        .home-tags {
            border-radius: 10px;
            background-color: #93C225;
            width: 100%;
            color: white;
            text-transform: uppercase;
            transition: .1s ease-in-out;
        }

        .home-tags:hover {
            color: white;
            background-color: #6b8d19;

        }
    </style>
    <div class="container">
        <div class="row mb-4 mt-md-0 mt-4">
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword1))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword1) }}
                </a>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword2))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword2) }}
                </a>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword3))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword3) }}
                </a>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword4))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword4) }}
                </a>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword5))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword5) }}
                </a>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <a href="{{ route('search.get', ['search' => slug_format(trim($keyword6))]) }}"
                    class="home-tags externallink btn keyword-search">
                    #{{ trim($keyword6) }}
                </a>
            </div>
        </div>
    </div>
@endif
@php
    $social_media_link1 = isset($magicbox['social_media_link1']) ? $magicbox['social_media_link1'] : null;
    $social_media_link2 = isset($magicbox['social_media_link2']) ? $magicbox['social_media_link2'] : null;
@endphp

@if (!blank($social_media_link1) || !blank($social_media_link2))

    <div class="container">

        @if (!blank($social_media_link1) || !blank($social_media_link2))
            <div class="row">
                <div class="col-12">
                    <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                        <h2 class="text-black">Sosyal Medya İçerikleri</h2>
                        <div class="headline-block-indicator">
                            <div class="indicator-ball" style="background-color:#EC0000;"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4">
            @if (!blank($social_media_link1))
                <div class="col-6 col-md-6">
                    <div class="py-2 px-1 border-bottom border-info overflow-hidden video-link">
                        {!! $social_media_link1 !!}
                    </div>
                </div>
            @endif

            @if (!blank($social_media_link2))
                <div class="col-6 col-md-6">
                    <div class="py-2 px-1 border-bottom border-warning overflow-hidden video-link">
                        {!! $social_media_link2 !!}
                    </div>
                </div>
            @endif

        </div>

    </div>

@endif
