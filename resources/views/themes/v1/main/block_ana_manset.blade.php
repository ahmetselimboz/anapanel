

<div class="container">
    <div class="row">
        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))
            <div class="col-12 col-lg-8 mb-4"><!-- (Ana Manşet) -->

                <div class="headline-block  overflow-hidden rounded-1">

                    <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                        <div class="carousel-inner">
                            @php $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json'); @endphp

							@foreach($ana_mansetler as $manset_key => $ana_manset)

                                @if($manset_key!=($magicbox["mansetsabitreklamno"]-1))
                                    <a href="{{ route('post', ['categoryslug'=>isset($ana_manset["categoryslug"]) ? $ana_manset["categoryslug"] : "kategorisiz",'slug'=>$ana_manset["slug"],'id'=>$ana_manset["id"]]) }}"
                                    class="externallink" title="{{ html_entity_decode($ana_manset["title"]) }}">
                                        <div class="carousel-item @if($manset_key==0) active @endif ">
                                            <div class="headline-item">
                                                <div class="headline-image">
                                                    <img width="100%" height="510" class="lazy mobilserbest2" src="{{ imageCheck($ana_manset["images"]) }}" alt="{{html_entity_decode($ana_manset["title"])}}" >
                                                </div>
                                                <div class="headline-title bg-dark-gradiant mobilboyutla">
                                                    @if(isset($ana_manset["show_title_slide"])&&$ana_manset["show_title_slide"]==0)
                                                        @if(isset($magicbox["mansetbaslik"]) and $magicbox["mansetbaslik"]==0)
                                                        <h1 class="text-uppercase">{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset["title"]), 150) }}</h1>
                                                        @endif
                                                    @endif
                                                    @if($magicbox["mansetaciklama"]==0)
                                                        <p class="text-truncate-line-3">{{ isset($ana_manset["description"]) ? html_entity_decode($ana_manset["description"]) : "..." }} </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    @if($magicbox["mansetsabitreklamno"]!=null and $ads21!=null and (adsCheck($ads21->id) && adsCheck($ads21->id)->publish==0))
                                        @php $adsR = $manset_key; @endphp
                                        @if(adsCheck($ads21->id)->type==1)
                                            {!! adsCheck($ads21->id)->code !!}
                                        @else
                                            <a href="{{ adsCheck($ads21->id)->url }}" class="externallink" title="Reklam {{$ads21->id}}">
                                                <div class="carousel-item @if($manset_key==0) active @endif ">
                                                    <div class="headline-item">
                                                        <div class="headline-image"><img width="100%" class="lazy mobilserbest2" src="{{ adsCheck($ads21->id)->images }}" alt="Reklam {{$ads21->id}}" data-type="{{ adsCheck($ads21->id)->type }}" height="{{ adsCheck($ads21->id)->height }}" height="510" width="{{ adsCheck($ads21->id)->width }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endif
                                    @else
                                    <!-- Tekrar Manşet haberi çevir -->
                                        <a href="{{ route('post', ['categoryslug'=>isset($ana_manset["categoryslug"]) ? $ana_manset["categoryslug"] : "kategorisiz",'slug'=>$ana_manset["slug"],'id'=>$ana_manset["id"]]) }}" class="externallink" title="{{ html_entity_decode($ana_manset["title"]) }}">
                                            <div class="carousel-item">
                                                <div class="headline-item">
                                                    <div class="headline-image">
                                                        <img width="100%" height="510" class="lazy mobilserbest2" src="{{ imageCheck($ana_manset["images"]) }}" alt="{{html_entity_decode($ana_manset["title"])}}" >
                                                    </div>
                                                    <div class="headline-title bg-dark-gradiant mobilboyutla">
                                                        @if($ana_manset["show_title_slide"]==0)
                                                            @if(isset($magicbox["mansetbaslik"]) and $magicbox["mansetbaslik"]==0)
                                                                <h1 class="text-uppercase">{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset["title"]), 150) }}</h1>
                                                            @endif
                                                        @endif
                                                        @if($magicbox["mansetaciklama"]==0)
                                                            <p class="text-truncate-line-3">{{ isset($ana_manset["description"]) ? html_entity_decode($ana_manset["description"]) : "..." }} </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                @endif

                            @endforeach
                        </div>
                        <div class="carousel-indicators">
                            @foreach($ana_mansetler as $manset_key_number => $ana_manset)
                                <button type="button" data-bs-config="{'delay':0}" data-bs-target="#headlineCarousel" data-bs-slide-to="{{$manset_key_number}}" @if($manset_key_number==0) class="active" aria-current="true" @endif> @if(isset($adsR) and $adsR==$manset_key_number) R @else {{$manset_key_number+1}} @endif</button>
                                @if(isset($magicbox["mansetlimit"])) @if($magicbox["mansetlimit"]==$manset_key) @break @endif @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning"> Ana Manşet Bulunamadı </div>
        @endif


    <div class="col-12 col-lg-4 mb-4">
        <div class="row">
            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json'))

                    @php $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp

                @if ($design == 'default' || $design == 1)
                        <div class="col-12 mb-4">
                            <div id="featuredCarousel" class="carousel bg-black" data-bs-ride="carousel"
                                data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                                        <div class="carousel-item @if ($minimanset_key == 0) active @endif">
                                            <a href="{{ route('post', ['categoryslug' => isset($mini_manset['categoryslug']) ? $mini_manset['categoryslug'] : 'kategorisiz', 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                                title="{{ html_entity_decode($mini_manset['title']) }}"
                                                class="externallink">
                                                <div class="featured-item">
                                                    <div class="featured-image">
                                                        <img src="{{ imageCheck($mini_manset['images']) }}"
                                                            alt="{{ html_entity_decode($mini_manset['title']) }}"
                                                            class="lazy">
                                                    </div>

                                                </div>
                                            </a>
                                        </div>
                                        @if ($minimanset_key == 14)
                                        @break
                                    @endif
                                @endforeach
                            </div>
                            <div class="carousel-indicators">
                                @foreach ($mini_mansetler as $minimanset_key_number => $mini_manset)
                                    <button type="button" data-bs-config="{'delay':0}"
                                        data-bs-target="#featuredCarousel"
                                        data-bs-slide-to="{{ $minimanset_key_number }}"
                                        @if ($minimanset_key_number == 0) class="active" aria-current="true" @endif>{{ $minimanset_key_number + 1 }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @foreach ($mini_mansetler as $minimanset_key_son => $mini_manset)
                        @if ($minimanset_key_son == 15)
                            <div class="col-12">
                                <div class="card overflow-hidden position-relative">
                                    <a href="{{ route('post', ['categoryslug' => isset($mini_manset['categoryslug']) ? $mini_manset['categoryslug'] : 'kategorisiz', 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                        class="externallink"
                                        title="{{ html_entity_decode($mini_manset['title']) }}">
                                        <img class="card-img-top lazy"
                                            src="{{ imageCheck($mini_manset['images']) }}"
                                            alt="{{ html_entity_decode($mini_manset['title']) }}">
                                    </a>
                                    <div class="card-body position-absolute bottom-0 start-0 end-0 p-0">
                                        <a href="{{ route('post', ['categoryslug' => isset($mini_manset['categoryslug']) ? $mini_manset['categoryslug'] : 'kategorisiz', 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                            class="externallink"
                                            title="{{ html_entity_decode($mini_manset['title']) }}">
                                            <h5 class="card-title bg-dark-gradiant text-white p-3 m-0">
                                                {{ html_entity_decode($mini_manset['title']) }}</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <a href="{{ $magicbox['fb'] }}" class="btn text-white mb-3" style="background: #30487b;"> <i
                            class="fa fa-facebook"></i> Facebook Takip Et </a>
                    <a href="{{ $magicbox['tw'] }}" class="btn text-white mb-3" style="background: #2ba9e1;"> <i
                            class="fa fa-facebook"></i> Twitter Takip Et </a>
                    <a href="{{ $magicbox['in'] }}" class="btn text-white mb-3" style="background: #BD0082;"> <i
                            class="fa fa-facebook"></i> Instagram Takip Et </a>
                    <a href="{{ $magicbox['yt'] }}" class="btn text-white mb-3" style="background: #f74c53;"> <i
                            class="fa fa-facebook"></i> Youtube Abone Ol </a>
                    <a href="{{ $magicbox['ln'] }}" class="btn text-white mb-3" style="background: #005f8d;"> <i
                            class="fa fa-facebook"></i> Linkedin Abone Ol </a>
                @else
                    <style>
                        .trending-top {
                            height: 100%;
                            overflow: hidden;


                        }

                          .trending-top ul {
                            width: 100%;
                            margin-bottom: 13px;
                        }


                        .trending-top ul li {
                            display: block;
                            width: 100%;
                            padding: 12px 15px;
                            border-bottom: 1px solid #E6E6E6;
                            transition: background-color .2s ease-in-out;
                            position: relative;
                            border-radius: var(--bs-border-radius-lg) !important;
                            /*margin-top: 0.5rem !important;*/
                        }

                        .trending-top ul li:before {
                            background-color: #000000
                        }

                        .trending-top ul li:hover {
                            background-color: #222222;
                            border-radius: 4%;
                        }

                        .trending-top ul li a {
                            display: flex;
                            align-items: center;
                            /*justify-content: space-between;*/
                            /* text-transform: uppercase; */
                        }

                        .trending-top ul li a p {
                            font-size: 15px;
                            line-height: 22px;
                            color: #010101;
                            display: -webkit-box;
                            -webkit-line-clamp: 3;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            padding-left: 16px;
                            font-weight: 600;
                            transition: color .2s ease-in-out;
                        }


                        .trending-top ul li:hover p {
                            color: white;
                        }

                        .trending-top ul li a figure {
                            /*width: 210px;*/
                            height: 100px;
                            margin: 0;
                            min-width: 185px;
                        }


                        .trending-top ul li a figure img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            max-height: 7rem;
                            max-width: 12rem;
                        }

                        .trending-bottom ul li span {
                            display: block;
                            font-size: 17px;
                            font-weight: 600;
                            text-align: center;
                            line-height: 30px;
                            height: 30px;
                            border-radius: 2px;
                            padding: 0 12px;
                            transition: color .2s ease-in-out, background-color .2s ease-in-out;
                            box-shadow: 0 1px 1px rgb(209 208 208);
                            margin-right: 6px;
                        }

                        .trending-bottom ul li span:hover {
                            background-color: #C90914;
                            color: #fff;
                            box-shadow: unset;
                        }

                        .trending-bottom {
                            background-color: #fff;
                            width: 100%;
                            padding: 1px;
                            padding-bottom: 0;
                        }

                        .trending-footer .social-rounded-button {
                            padding: 8px 0px 0px 0px;
                        }


                        .trending-footer a {
                            color: #fff;
                        }

                        .trending-footer a:hover {
                            color: #fff;
                        }

                                  .trending-footer .x {
                            background-color: #2ba9e1;
                            padding-right: 0px;
                            border-radius: 10%;
                            margin: 0px 0px 0px 3px;

                        }

                        .trending-footer .fb {
                            background-color: #30487b;
                            padding-right: 0px;
                            border-radius: 10%;
                            margin: 0px 0px 0px 3px;
                        }

                        .trending-footer .ins {
                            background-color: #BD0082;
                            padding-right: 0px;
                            border-radius: 10%;
                            margin: 0px 0px 0px 3px;
                        }

                        .trending-footer .ytb {
                            background-color:#f74c53;;
                            padding-right: 0px;
                            border-radius: 10%;
                            margin: 0px 0px 0px 3px;
                        }
                    </style>
                    <div class="col-12 mb-4">


                        <div class="trending">

                            {{-- <div class="trending-bottom">
                                <ul class="text-center list-unstyled">
                                    <li> <span class="fw-bold">Öne Çıkan Gelişmeler </span> </li>
                                </ul>
                            </div> --}}
                            <div class="trending-top">
                                <ul class="list-unstyled">
                                    @foreach (array_slice($mini_mansetler, 0, 4) as $key=>$mini_manset)
                                        <li class="bg-gray {{$key == 0 ? 'mt-0':'mt-2'}}">
                                            <a href="{{ route('post', ['categoryslug' => isset($mini_manset['categoryslug']) ? $mini_manset['categoryslug'] : 'kategorisiz', 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                                                class="externallink"
                                                title="{{ html_entity_decode($mini_manset['title']) }}">

                                                <figure>
                                                    <img src="{{ imageCheck($mini_manset['images']) }}"
                                                        alt="{{ $mini_manset['title'] }}">
                                                </figure>
                                                <p class="text-intherit">{{ $mini_manset['title'] }}</p>

                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="trending-footer">
                                <div class="d-flex">
                                    <div class="col x">
                                        <a href="{{ $magicbox['tw'] }}" class="social-rounded-button d-flex"
                                            target="_blank">
                                            <div class="icon-social-x"
                                                style="background-position: left; margin:3px 0px 6px 4px;"></div>
                                            <span class=""> Takip Et</span>
                                        </a>
                                    </div>
                                    <div class="col fb">
                                        <a href="{{ $magicbox['fb'] }}" class="social-rounded-button d-flex"
                                            target="_blank">
                                            <div class="icon-social-facebook"
                                                style="background-position: left; margin:3px 0px 6px 4px;"></div>
                                            <span class=""> Takip Et</span>
                                        </a>
                                    </div>
                                    <div class="col ins">
                                        <a href="{{ $magicbox['in'] }}" class="social-rounded-button d-flex"
                                            target="_blank">
                                            <div class="icon-social-instagram"
                                                style="background-position: left; margin:3px 0px 6px 4px;"></div>
                                            <span class=""> Takip Et</span>
                                        </a>
                                    </div>
                                    <div class="col ytb">
                                        <a href="{{ $magicbox['yt'] }}" class="social-rounded-button d-flex"
                                            target="_blank">
                                            <div class="icon-social-youtube"
                                                style="background-position: left; margin:3px 0px 6px 4px;"></div>
                                            <span class=""> Takip Et</span>
                                        </a>
                                    </div>
                                </div>



                            </div>

                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-warning"> Mini Manşet Bulunamadı </div>
            @endif


        </div>

    </div>

    </div>
</div>


<style>
    @if (isset($magicbox['mansetnumaracolor']))
        #headlineCarousel .carousel-indicators {
            background-color: {{ $magicbox['mansetnumaracolor'] }} !important;
        }
    @endif
    @if (isset($magicbox['mansetnumaracolortext']))
        #headlineCarousel .carousel-indicators [data-bs-target] {
            color: {{ $magicbox['mansetnumaracolortext'] }} !important;
        }
    @endif

    @media only screen and (max-width: 991px) {
        #headlineCarousel .carousel-indicators {
            background-color: #ccc !important;
        }

    }

</style>
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
