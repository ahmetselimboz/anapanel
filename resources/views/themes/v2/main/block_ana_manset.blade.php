<div class="container mobyok">
    <div class="row">
        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))
            <div class="col-12 col-lg-8 mb-2">
                <div class="headline-block overflow-hidden rounded-1">
                    <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                        <div class="carousel-inner">
                            @php $sayim = 0; @endphp

                            @php $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json',true,);

                            @endphp

                            @foreach ($ana_mansetler as $manset_key => $ana_manset)

                                @if ($loop->iteration > 15)
                                    @continue

                                @endif
                                    @php $sayim++; @endphp
                                    @if ($manset_key != $magicbox['mansetsabitreklamno'] - 1)
                                        <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                                            class="externallink" title="{{ html_entity_decode($ana_manset['title']) }}">
                                            <div class="carousel-item @if ($manset_key == 0) active @endif ">
                                                <div class="headline-item">
                                                    <div class="headline-image"> <img width="100%"
                                                            src="{{ route('resizeImage', ['i_url' => $ana_manset['images'], 'w' => 777, 'h' => 510]) }}"
                                                            alt="{{ html_entity_decode($ana_manset['title']) }}"
                                                            class="lazy"> </div>
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
                                    @else

                                        @if ($magicbox['mansetsabitreklamno'] != null and $ads21 != null and adsCheck($ads21) && adsCheck($ads21)->publish == 0)
                                            @php
                                            $adsR = $loop->iteration;
                                            $ads21 = adsCheck($ads21);
                                            @endphp
                                            @if ($ads21->type == 1)
                                                {!! $ads21->code !!}
                                            @else
                                                <a href="{{ $ads21->url }}" class="externallink"
                                                    title="Reklam {{ $ads21->id }}">
                                                    <div
                                                        class="carousel-item @if ($manset_key == 0) active @endif ">
                                                        <div class="headline-item">
                                                            <div class="headline-image"> <img class="lazy"
                                                                    src="{{ $ads21->images }}"
                                                                    {{-- src="{{ imageCheck($ads21->images) }}" --}}
                                                                    alt="Reklam {{ $ads21->id }}"
                                                                    data-type="{{ $ads21->type }}"
                                                                    height="100%"
                                                                    {{-- height="{{ $ads21->height }}" --}}
                                                                    width="{{ $ads21->width }}"> </div>
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
                                                        <div class="headline-image"> <img width="100%"
                                                                src="{{ route('resizeImage', ['i_url' => $ana_manset['images'], 'w' => 777, 'h' => 510]) }}"
                                                                alt="{{ html_entity_decode($ana_manset['title']) }}"
                                                                class="lazy"> </div>
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
                            @php
                                $sayim = 0;
                            @endphp
                            @foreach ($ana_mansetler as $manset_key_number => $ana_manset)
                                @if ($loop->iteration > 15)
                                    @continue
                                @endif
                                @php $sayim++; @endphp

                                <button type="button" data-bs-target="#headlineCarousel"
                                    data-bs-slide-to="{{ $manset_key_number }}"
                                    @if ($loop->iteration == 1) class="active" aria-current="true" @endif>

                                    <!-- Masaüstü için sayılar, mobil için noktalar -->
                                    <span class="d-none d-md-inline">{{isset($adsR) && $adsR== $loop->iteration ? "R" : $loop->iteration  }}</span>
                                    <span class="d-inline d-md-none">●</span>

                                </button>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning"> Ana Manşet Bulunamadı</div>
        @endif
        <div class="col-12 col-lg-4 mb-2">
            <div class="row h-100">
                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json'))
                    @php $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp
                    <div class="col-12">
                        @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                            <div class="spot-sabit-manset spot-8 mb-2" style="height: 287px">
                                <a href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                    title="{{ html_entity_decode($mini_manset['title']) }}">
                                    <div class="spot-sabit-manset-img-box">
                                        <img class="spot-sabit-manset-img"
                                            src="{{ route('resizeImage', ['i_url' => $mini_manset['images'], 'w' => 377, 'h' => 250]) }}"
                                            alt="{{ html_entity_decode($mini_manset['title']) }}" />
                                    </div>

                                    <p>
                                        <b>{{ $mini_manset['categorytitle'] }}</b><span>{{ html_entity_decode($mini_manset['title']) }}</span>
                                    </p>
                                </a>

                            </div>
                            @if ($minimanset_key == 1)
                                @break
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning"> Mini Manşet Bulunamadı</div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<div class="container mobvar">
    <div class="spotlar">
        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))
            <div class="col-12 col-lg-8 mb-4">
                <div class="headline-block overflow-hidden rounded-1">
                    <div id="headlineMobileCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                        <div class="carousel-inner">

                            @php $sayim = 0;
                                $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json',);
                            @endphp
                            @foreach ($ana_mansetler as $manset_key => $ana_manset)
                                @if ($sayim > 14)
                                    @break
                                @endif
                                @php $sayim++;
                                     $extra = json_decode($ana_manset['extra'],true);
                                     $miniImage = isset($extra['mini_images']) && !blank($extra['mini_images'])
                                     ? route('resizeImage', ['i_url' => $extra['mini_images'], 'w' => 400, 'h' => 500])
                                     : route('resizeImage', ['i_url' => ($ana_manset['images']), 'w' => 777, 'h' => 510]);
                                 @endphp
                                @if ($manset_key != $magicbox['mansetsabitreklamno'] - 1)
                                    <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                                        class="externallink" title="{{ html_entity_decode($ana_manset['title']) }}">
                                        <div class="carousel-item @if ($manset_key == 0) active @endif ">
                                            <div class="headline-item">
                                                <div class="headline-image">
                                                    <img width="100%" src="{{  $miniImage }}" alt="{{ html_entity_decode($ana_manset['title']) }}" class="lazy">
                                                </div>
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
                                @else

                                    @if (
                                        $magicbox['mansetsabitreklamno'] != null and
                                            $ads21 != null and
                                            adsCheck($ads21) && adsCheck($ads21)->publish == 0)
                                        @php $adsR = $manset_key; @endphp @if (adsCheck($ads21)->type == 1)
                                            {!! adsCheck($ads21)->code !!}
                                        @else
                                            <a href="{{ adsCheck($ads21)->url }}" class="externallink"
                                                title="Reklam {{ $ads21->id }}">
                                                <div
                                                    class="carousel-item @if ($manset_key == 0) active @endif ">
                                                    <div class="headline-item">
                                                        <div class="headline-image"><img class="lazy"
                                                                src="{{ adsCheck($ads21)->images }}"
                                                                alt="Reklam {{ $ads21->id }}"
                                                                data-type="{{ adsCheck($ads21)->type }}"
                                                                height="100%"
                                                                width="{{ adsCheck($ads21)->width }}">
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
                                                            src="{{ route('resizeImage', ['i_url' => ($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
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
                                    data-bs-target="#headlineMobileCarousel" data-bs-slide="prev"></span>
                                <span class="visually-hidden">Önceki</span>
                            </button>

                            <button class="carousel-control-next d-flex" type="button">
                                <span class="carousel-control-next-icon" aria-hidden="true"
                                    data-bs-target="#headlineMobileCarousel" data-bs-slide="next"></span>
                                <span class="visually-hidden">Sonraki</span>
                            </button>

                        </div>


                        <div class="carousel-indicators">
                            @php
                                $sayim = 0;
                            @endphp
                            @foreach ($ana_mansetler as $manset_key_number => $ana_manset)
                                @if ($loop->iteration > 15)
                                    @break
                                @endif
                                @php $sayim++; @endphp

                                <button type="button" data-bs-config="{'delay':0}"
                                    data-bs-target="#headlineMobileCarousel"
                                    data-bs-slide-to="{{ $manset_key_number }}"
                                    @if ($manset_key_number == 0) class="active" aria-current="true" @endif>

                                    <!-- Masaüstü için sayılar, mobil için noktalar -->
                                    <span class="d-none d-md-inline">{{ $manset_key_number + 1 }}</span>
                                    <span class="d-inline d-md-none">●</span>

                                </button>
                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning"> Ana Manşet Bulunamadı</div>
        @endif
        <div class="tmz"></div>
        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json')) @php
        $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp
            <div class="col-12">
                <div class="yenilistele" id="yenilistele">
                    <div class="yenilistekutu">
                        @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                            <div class="yeniliste">
                                <a href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                    title="{{ html_entity_decode($mini_manset['title']) }}">
                                    <img src="{{ route('resizeImage', ['i_url' => $mini_manset['images'], 'w' => 140, 'h' => 100]) }}"
                                        alt="{{ html_entity_decode($mini_manset['title']) }}" />
                                    <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                                </a>
                            </div>
                            @if ($minimanset_key == 3)
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="yenilistekutu">
                        @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                            @if ($minimanset_key < 4)
                            @else
                                <div class="yeniliste">
                                    <a href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                        title="{{ html_entity_decode($mini_manset['title']) }}">
                                        <img src="{{ route('resizeImage', ['i_url' => $mini_manset['images'], 'w' => 140, 'h' => 100]) }}"
                                            alt="{{ html_entity_decode($mini_manset['title']) }}" />
                                        <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                                    </a>
                                </div>
                                @endif @if ($minimanset_key == 7)
                                    @break
                                @endif
                            @endforeach
                    </div>
                    <div class="yenilistekutu">
                        @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                            @if ($minimanset_key < 8)
                            @else
                                <div class="yeniliste">
                                    <a href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                        title="{{ html_entity_decode($mini_manset['title']) }}">
                                        <img src="{{ route('resizeImage', ['i_url' => $mini_manset['images'], 'w' => 140, 'h' => 100]) }}"
                                            alt="{{ html_entity_decode($mini_manset['title']) }}" />
                                        <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                                    </a>
                                </div>
                                @endif @if ($minimanset_key == 11)
                                    @break
                                @endif
                            @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="tmz"></div>
<div class="container">
    <div class="row mt-0 mb-4">
        @php
            $weather_link = isset($magicbox['weather_link']) ? $magicbox['weather_link'] : null;
            $pharmacy_link = isset($magicbox['pharmacy_link']) ? $magicbox['pharmacy_link'] : null;
            $deceased_link = isset($magicbox['deceased_link']) ? $magicbox['deceased_link'] : null;
            $traffic_link = isset($magicbox['traffic_link']) ? $magicbox['traffic_link'] : null;
        @endphp
        @if (!blank($weather_link))
            <div class="col-6 col-md-3 mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $weather_link }}" class="btn btn-info w-100 text-white" target="_blank">

                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-cloud text-white"></i>
                        Hava Durumu
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-cloud text-white"></i>
                        Hava Durumu
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($pharmacy_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $pharmacy_link }}" class="btn btn-danger w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-heartbeat"></i>
                        Nöbetçi Eczaneler
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-heartbeat"></i>
                        Nöbetçi Eczaneler
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($deceased_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $deceased_link }}" class="btn btn-dark w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-frown-o"></i>
                        Vefat Edenler
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-frown-o"></i>
                        Vefat Edenler
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($traffic_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $traffic_link }}" class="btn btn-success w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-car"></i>
                        Trafik Yol Durumu
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-car"></i>
                        Trafik Yol Durumu
                    </h6>
                </a>
            </div>
        @endif
    </div>
</div>

@php
	$social_media_link1 = isset($magicbox['social_media_link1']) ? $magicbox['social_media_link1'] : null;
	$social_media_link2 = isset($magicbox['social_media_link2']) ? $magicbox['social_media_link2'] : null;
	$social_media_link3 = isset($magicbox['social_media_link3']) ? $magicbox['social_media_link3'] : null;
	$social_media_link4 = isset($magicbox['social_media_link4']) ? $magicbox['social_media_link4'] : null;
@endphp

@if (!blank($social_media_link1)||!blank($social_media_link2)||!blank($social_media_link3)||!blank($social_media_link4))

<style>
    .video-link iframe {
        width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
    }
</style>

<div class="container">

    @if (!blank($social_media_link1)||!blank($social_media_link2)||!blank($social_media_link3)||!blank($social_media_link4))
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
            <div class="col-12 col-md-6">
                <div class="py-2 px-1 border-bottom border-info overflow-hidden video-link">
                    {!!$social_media_link1!!}
                </div>
            </div>
        @endif

        @if (!blank($social_media_link2))
            <div class="col-12 col-md-6">
                <div class="py-2 px-1 border-bottom border-warning overflow-hidden video-link">
                    {!!$social_media_link2!!}
                </div>
            </div>
        @endif

    </div>

</div>

@endif